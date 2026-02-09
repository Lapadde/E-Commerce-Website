<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\OrderModel;
use App\Models\OrderDetailModel;
use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\UserRoleModel;
use App\Models\AuditLogModel;
use Midtrans\Snap;
use Midtrans\Exception as MidtransException;

class CheckoutController extends BaseController
{
    protected $productModel;
    protected $orderModel;
    protected $orderDetailModel;
    protected $userModel;
    protected $roleModel;
    protected $userRoleModel;
    protected $auditLogModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->orderModel = new OrderModel();
        $this->orderDetailModel = new OrderDetailModel();
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        $this->userRoleModel = new UserRoleModel();
        $this->auditLogModel = new AuditLogModel();
    }

    private function getCartKey()
    {
        $customerId = session()->get('customer_id');
        if (!$customerId) {
            // This should not happen as checkout routes are protected by customerAuth filter
            // But just in case, return null to prevent accessing old cart
            return null;
        }
        return 'cart_' . $customerId;
    }

    public function index()
    {
        $cartKey = $this->getCartKey();
        $cart = session()->get($cartKey) ?? [];
        
        if (empty($cart)) {
            return redirect()->to('/cart')->with('error', 'Keranjang belanja kosong');
        }
        
        // Calculate cart total
        $cartItems = [];
        $total = 0;
        
        foreach ($cart as $productId => $item) {
            $product = $this->productModel->find($productId);
            if ($product && $product['stock'] >= $item['quantity']) {
                $item['product'] = $product;
                $item['subtotal'] = $product['price'] * $item['quantity'];
                $total += $item['subtotal'];
                $cartItems[] = $item;
            }
        }
        
        if (empty($cartItems)) {
            return redirect()->to('/cart')->with('error', 'Tidak ada produk yang valid di keranjang');
        }
        
        // Get customer info if logged in
        $customer = null;
        $customerId = session()->get('customer_id');
        if ($customerId) {
            $customer = $this->userModel->find($customerId);
        }
        
        $data = [
            'title'      => 'Checkout',
            'cartItems'  => $cartItems,
            'total'      => $total,
            'customer'   => $customer,
            'cartCount'  => array_sum(array_column($cart, 'quantity')),
        ];

        return view('shop/checkout', $data);
    }

    public function process()
    {
        $cartKey = $this->getCartKey();
        if (!$cartKey) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        $cart = session()->get($cartKey) ?? [];
        
        if (empty($cart)) {
            return redirect()->to('/cart')->with('error', 'Keranjang belanja kosong');
        }
        
        $rules = [
            'phone'            => 'required',
            'shipping_address' => 'required|min_length[10]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Check stock availability
        $cartItems = [];
        $total = 0;
        
        foreach ($cart as $productId => $item) {
            $product = $this->productModel->find($productId);
            
            if (!$product) {
                return redirect()->to('/cart')->with('error', 'Produk tidak ditemukan');
            }
            
            if ($product['stock'] < $item['quantity']) {
                return redirect()->to('/cart')->with('error', 'Stok produk "' . $product['name'] . '" tidak mencukupi');
            }
            
            $subtotal = $product['price'] * $item['quantity'];
            $total += $subtotal;
            
            $cartItems[] = [
                'product'  => $product,
                'quantity' => $item['quantity'],
                'subtotal' => $subtotal,
            ];
        }
        
        // Get customer from session (already logged in)
        $customerId = session()->get('customer_id');
        
        if (!$customerId) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        
        // Update customer phone if provided
        $phone = $this->request->getPost('phone');
        if ($phone) {
            $this->userModel->update($customerId, ['phone' => $phone]);
        }
        
        // Get customer data
        $customer = $this->userModel->find($customerId);
        
        // Create order
        $orderData = [
            'customer_id'      => $customerId,
            'order_date'       => date('Y-m-d H:i:s'),
            'amount'           => $total,
            'shipping_address' => $this->request->getPost('shipping_address'),
            'order_status'     => 'pending',
            'payment_status'   => 'pending',
        ];
        
        $orderId = $this->orderModel->insert($orderData);
        
        if (!$orderId) {
            return redirect()->back()->with('error', 'Gagal membuat pesanan');
        }
        
        // Create order details (don't update stock yet - wait for payment confirmation)
        foreach ($cartItems as $item) {
            $this->orderDetailModel->insert([
                'order_id'   => $orderId,
                'product_id' => $item['product']['id'],
                'price'      => $item['product']['price'],
                'quantity'   => $item['quantity'],
            ]);
        }
        
        // Prepare Midtrans transaction
        helper('midtrans');
        
        // Validate Midtrans configuration
        $config = getMidtransConfig();
        if (empty($config['server_key'])) {
            log_message('error', 'Midtrans: MIDTRANS_SERVER_KEY tidak dikonfigurasi');
            $this->orderModel->delete($orderId);
            return redirect()->back()->with('error', 'Konfigurasi pembayaran belum lengkap. Silakan hubungi administrator.');
        }
        
        try {
            initMidtrans();
        } catch (\Exception $e) {
            log_message('error', 'Midtrans Init Error: ' . $e->getMessage());
            $this->orderModel->delete($orderId);
            return redirect()->back()->with('error', 'Gagal menginisialisasi pembayaran: ' . $e->getMessage());
        }
        
        // Prepare item details for Midtrans
        $itemDetails = [];
        foreach ($cartItems as $item) {
            $itemDetails[] = [
                'id'       => (string)$item['product']['id'],
                'price'    => (float)$item['product']['price'],
                'quantity' => (int)$item['quantity'],
                'name'     => substr($item['product']['name'], 0, 50), // Max 50 chars
            ];
        }
        
        // Validate total amount matches item details
        $calculatedTotal = 0;
        foreach ($itemDetails as $item) {
            $calculatedTotal += $item['price'] * $item['quantity'];
        }
        
        if (abs($calculatedTotal - $total) > 0.01) {
            log_message('error', 'Midtrans: Total mismatch. Calculated: ' . $calculatedTotal . ', Expected: ' . $total);
            $this->orderModel->delete($orderId);
            return redirect()->back()->with('error', 'Terjadi kesalahan dalam perhitungan. Silakan coba lagi.');
        }
        
        // Prepare transaction data
        $transaction = [
            'transaction_details' => [
                'order_id'     => 'ORDER-' . $orderId . '-' . time(),
                'gross_amount' => (float)$total,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => substr($customer['full_name'] ?? 'Customer', 0, 20),
                'email'      => $customer['email'] ?? '',
                'phone'      => substr($phone ?? '', 0, 19),
                'billing_address' => [
                    'address' => substr($this->request->getPost('shipping_address'), 0, 200),
                ],
                'shipping_address' => [
                    'address' => substr($this->request->getPost('shipping_address'), 0, 200),
                ],
            ],
            'callbacks' => [
                'finish' => base_url('payment/finish'),
                'unfinish' => base_url('payment/unfinish'),
                'error' => base_url('payment/error'),
            ],
        ];
        
        try {
            // Get SNAP token
            $snapToken = Snap::getSnapToken($transaction);
            
            if (empty($snapToken)) {
                throw new \Exception('SNAP token kosong dari Midtrans');
            }
            
            // Update order with snap token
            $updateData = [
                'snap_token' => $snapToken,
            ];
            
            $updateResult = $this->orderModel->update($orderId, $updateData);
            
            // Check if update was successful
            if ($updateResult === false) {
                // Check if order exists
                $orderCheck = $this->orderModel->find($orderId);
                if (!$orderCheck) {
                    throw new \Exception('Order tidak ditemukan untuk diupdate');
                }
                
                // Log detailed error for debugging
                $errors = $this->orderModel->errors();
                log_message('error', 'Failed to update snap_token for order ' . $orderId . '. Errors: ' . json_encode($errors));
                log_message('error', 'Order exists: ' . json_encode($orderCheck));
                log_message('error', 'Update data: ' . json_encode($updateData));
                
                throw new \Exception('Gagal menyimpan token pembayaran. Silakan hubungi administrator.');
            }
            
            // Log activity
            $this->auditLogModel->logAction($customerId, "Membuat pesanan baru dengan Midtrans (Order ID: {$orderId}, Total: Rp " . number_format($total, 0, ',', '.') . ")");
            
            // Redirect to payment page
            return redirect()->to('/payment/process/' . $orderId);
            
        } catch (MidtransException $e) {
            // Midtrans specific error
            $errorMessage = $e->getMessage();
            log_message('error', 'Midtrans API Error: ' . $errorMessage);
            log_message('error', 'Midtrans Error Details: ' . json_encode([
                'order_id' => $orderId,
                'total' => $total,
                'server_key_set' => !empty($config['server_key']),
            ]));
            
            // Delete order if Midtrans fails
            $this->orderModel->delete($orderId);
            
            // Provide more specific error message
            if (strpos($errorMessage, '401') !== false || strpos($errorMessage, 'Unauthorized') !== false) {
                return redirect()->back()->with('error', 'Kredensial Midtrans tidak valid. Silakan hubungi administrator.');
            } elseif (strpos($errorMessage, '400') !== false) {
                return redirect()->back()->with('error', 'Data transaksi tidak valid. Silakan coba lagi.');
            } else {
                return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $errorMessage);
            }
            
        } catch (\Exception $e) {
            // General error
            $errorMessage = $e->getMessage();
            log_message('error', 'Midtrans General Error: ' . $errorMessage);
            log_message('error', 'Stack Trace: ' . $e->getTraceAsString());
            
            // Delete order if Midtrans fails
            $this->orderModel->delete($orderId);
            
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $errorMessage);
        }
    }

    public function success($orderId)
    {
        $order = $this->orderModel->select('orders.*, users.full_name as customer_name, users.email as customer_email')
                                  ->join('users', 'users.id = orders.customer_id')
                                  ->where('orders.id', $orderId)
                                  ->first();
        
        if (!$order) {
            return redirect()->to('/shop')->with('error', 'Pesanan tidak ditemukan');
        }
        
        $orderItems = $this->orderDetailModel->getItemsByOrder($orderId);
        
        $data = [
            'title'      => 'Pesanan Berhasil',
            'order'      => $order,
            'orderItems' => $orderItems,
        ];

        return view('shop/order_success', $data);
    }
}

