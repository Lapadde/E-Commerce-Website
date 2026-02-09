<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderDetailModel;
use App\Models\ProductModel;
use App\Models\AuditLogModel;
use App\Models\UserModel;
use Midtrans\Notification;
use Midtrans\Transaction;
use Midtrans\Snap;

class PaymentController extends BaseController
{
    protected $orderModel;
    protected $orderDetailModel;
    protected $productModel;
    protected $auditLogModel;
    protected $userModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderDetailModel = new OrderDetailModel();
        $this->productModel = new ProductModel();
        $this->auditLogModel = new AuditLogModel();
        $this->userModel = new UserModel();
    }

    /**
     * Display payment page with Midtrans SNAP
     */
    public function process($orderId)
    {
        $customerId = session()->get('customer_id');
        
        if (!$customerId) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        
        $order = $this->orderModel->getOrderWithDetails($orderId, $customerId);
        
        if (!$order) {
            return redirect()->to('/shop')->with('error', 'Pesanan tidak ditemukan');
        }
        
        // Check if order is already paid
        if ($order['payment_status'] === 'paid') {
            return redirect()->to('/checkout/success/' . $orderId)->with('info', 'Pesanan sudah dibayar');
        }
        
        // Check if order status is pending (only pending orders can be paid)
        if ($order['order_status'] !== 'pending' && $order['payment_status'] !== 'pending') {
            return redirect()->to('/orders/' . $orderId)->with('error', 'Pesanan ini tidak dapat dibayar');
        }
        
        $orderItems = $this->orderDetailModel->getItemsByOrder($orderId);
        
        helper('midtrans');
        $config = getMidtransConfig();
        
        // Validate Midtrans configuration
        if (empty($config['server_key'])) {
            log_message('error', 'Midtrans: MIDTRANS_SERVER_KEY tidak dikonfigurasi');
            return redirect()->back()->with('error', 'Konfigurasi pembayaran belum lengkap. Silakan hubungi administrator.');
        }
        
        // Generate snap_token if not exists or expired
        $snapToken = $order['snap_token'];
        
        if (empty($snapToken)) {
            try {
                initMidtrans();
                
                // Get customer data
                $customer = $this->userModel->find($customerId);
                
                // Prepare item details for Midtrans
                $itemDetails = [];
                foreach ($orderItems as $item) {
                    $itemDetails[] = [
                        'id'       => (string)$item['product_id'],
                        'price'    => (float)$item['price'],
                        'quantity' => (int)$item['quantity'],
                        'name'     => substr($item['product_name'], 0, 50), // Max 50 chars
                    ];
                }
                
                // Validate total amount matches item details
                $calculatedTotal = 0;
                foreach ($itemDetails as $item) {
                    $calculatedTotal += $item['price'] * $item['quantity'];
                }
                
                if (abs($calculatedTotal - $order['amount']) > 0.01) {
                    log_message('error', 'Midtrans: Total mismatch. Calculated: ' . $calculatedTotal . ', Expected: ' . $order['amount']);
                    return redirect()->back()->with('error', 'Terjadi kesalahan dalam perhitungan. Silakan hubungi administrator.');
                }
                
                // Prepare transaction data
                $transaction = [
                    'transaction_details' => [
                        'order_id'     => 'ORDER-' . $orderId . '-' . time(), // Unique order ID for Midtrans
                        'gross_amount' => (float)$order['amount'],
                    ],
                    'item_details' => $itemDetails,
                    'customer_details' => [
                        'first_name' => substr($customer['full_name'] ?? 'Customer', 0, 20),
                        'email'      => $customer['email'] ?? '',
                        'phone'      => substr($customer['phone'] ?? '', 0, 19),
                        'billing_address' => [
                            'address' => substr($order['shipping_address'], 0, 200),
                        ],
                        'shipping_address' => [
                            'address' => substr($order['shipping_address'], 0, 200),
                        ],
                    ],
                    'callbacks' => [
                        'finish' => base_url('payment/finish'),
                        'unfinish' => base_url('payment/unfinish'),
                        'error' => base_url('payment/error'),
                    ],
                ];
                
                // Get SNAP token
                $snapToken = Snap::getSnapToken($transaction);
                
                if (empty($snapToken)) {
                    throw new \Exception('SNAP token kosong dari Midtrans');
                }
                
                // Update order with snap token
                $this->orderModel->update($orderId, [
                    'snap_token' => $snapToken,
                ]);
                
                log_message('info', 'PaymentController: Generated new snap_token for order ' . $orderId);
                
            } catch (\Midtrans\Exception $e) {
                log_message('error', 'Midtrans API Error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
            } catch (\Exception $e) {
                log_message('error', 'PaymentController Error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal memproses pembayaran. Silakan coba lagi.');
            }
        }
        
        $data = [
            'title'      => 'Pembayaran',
            'order'      => $order,
            'orderItems' => $orderItems,
            'snapToken'  => $snapToken,
            'clientKey'  => $config['client_key'],
            'isProduction' => filter_var($config['is_production'], FILTER_VALIDATE_BOOLEAN),
        ];
        
        return view('shop/payment', $data);
    }

    /**
     * Handle Midtrans notification (webhook)
     */
    public function notification()
    {
        helper('midtrans');
        initMidtrans();
        
        try {
            $notification = new Notification();
            $transaction = $notification->transaction_status;
            $type = $notification->payment_type;
            $orderId = $notification->order_id;
            $fraud = $notification->fraud_status;
            
            // Extract order ID from Midtrans order_id format: ORDER-{id}-{timestamp}
            preg_match('/ORDER-(\d+)-/', $orderId, $matches);
            $actualOrderId = $matches[1] ?? null;
            
            if (!$actualOrderId) {
                log_message('error', 'Midtrans Notification: Invalid order ID format - ' . $orderId);
                return $this->response->setStatusCode(400);
            }
            
            $order = $this->orderModel->find($actualOrderId);
            
            if (!$order) {
                log_message('error', 'Midtrans Notification: Order not found - ' . $actualOrderId);
                return $this->response->setStatusCode(404);
            }
            
            // Handle transaction status
            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        // Set payment status to challenge
                        $this->orderModel->update($actualOrderId, [
                            'payment_status' => 'challenge',
                            'transaction_id' => $orderId,
                        ]);
                    } else {
                        // Set payment status to paid
                        $this->updateOrderToPaid($actualOrderId, $orderId);
                    }
                }
            } else if ($transaction == 'settlement') {
                // Set payment status to paid
                $this->updateOrderToPaid($actualOrderId, $orderId);
            } else if ($transaction == 'pending') {
                // Set payment status to pending
                $this->orderModel->update($actualOrderId, [
                    'payment_status' => 'pending',
                    'transaction_id' => $orderId,
                ]);
            } else if ($transaction == 'deny') {
                // Set payment status to failed
                $this->orderModel->update($actualOrderId, [
                    'payment_status' => 'failed',
                    'transaction_id' => $orderId,
                ]);
            } else if ($transaction == 'expire') {
                // Set payment status to expired
                $this->orderModel->update($actualOrderId, [
                    'payment_status' => 'expired',
                    'transaction_id' => $orderId,
                ]);
            } else if ($transaction == 'cancel') {
                // Set payment status to cancelled
                $this->orderModel->update($actualOrderId, [
                    'payment_status' => 'cancelled',
                    'transaction_id' => $orderId,
                ]);
            }
            
            // Log notification
            log_message('info', 'Midtrans Notification: Order ' . $actualOrderId . ' - Status: ' . $transaction);
            
            return $this->response->setStatusCode(200);
            
        } catch (\Exception $e) {
            log_message('error', 'Midtrans Notification Error: ' . $e->getMessage());
            return $this->response->setStatusCode(500);
        }
    }

    /**
     * Clear customer cart after successful payment
     */
    private function clearCart($customerId)
    {
        if ($customerId) {
            $cartKey = 'cart_' . $customerId;
            session()->remove($cartKey);
            log_message('info', 'PaymentController: Cart cleared for customer ID: ' . $customerId);
        }
    }

    /**
     * Update order to paid status and update stock
     */
    private function updateOrderToPaid($orderId, $transactionId)
    {
        $order = $this->orderModel->find($orderId);
        
        if (!$order) {
            log_message('error', 'PaymentController: Order not found - ' . $orderId);
            return;
        }
        
        // Check if already paid to prevent duplicate stock update
        if ($order['payment_status'] === 'paid') {
            log_message('info', 'PaymentController: Order already paid - ' . $orderId);
            return; // Already paid, skip
        }
        
        // Start database transaction
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            // Update payment status
            $this->orderModel->update($orderId, [
                'payment_status' => 'paid',
                'order_status'   => 'processing',
                'transaction_id' => $transactionId,
                'payment_method' => 'midtrans',
            ]);
            
            // Update stock (only if not already updated)
            $orderItems = $this->orderDetailModel->getItemsByOrder($orderId);
            foreach ($orderItems as $item) {
                $product = $this->productModel->find($item['product_id']);
                if ($product) {
                    // Check stock availability
                    if ($product['stock'] < $item['quantity']) {
                        throw new \Exception('Insufficient stock for product ID: ' . $item['product_id']);
                    }
                    
                    $newStock = $product['stock'] - $item['quantity'];
                    $this->productModel->update($item['product_id'], ['stock' => $newStock]);
                }
            }
            
            // Commit transaction
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Database transaction failed');
            }
            
            // Clear cart after successful payment
            $this->clearCart($order['customer_id']);
            
            // Log activity
            $this->auditLogModel->logAction($order['customer_id'], "Pembayaran berhasil untuk Order ID: {$orderId}");
            
            log_message('info', 'PaymentController: Order payment successful - ' . $orderId);
            
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'PaymentController: Error updating order to paid - ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle payment finish (redirect from Midtrans)
     */
    public function finish()
    {
        $orderId = $this->request->getGet('order_id');
        
        if (!$orderId) {
            return redirect()->to('/shop')->with('error', 'Order ID tidak ditemukan');
        }
        
        // Extract actual order ID
        preg_match('/ORDER-(\d+)-/', $orderId, $matches);
        $actualOrderId = $matches[1] ?? null;
        
        if (!$actualOrderId) {
            return redirect()->to('/shop')->with('error', 'Format Order ID tidak valid');
        }
        
        // Check payment status
        helper('midtrans');
        initMidtrans();
        
        try {
            $status = Transaction::status($orderId);
            $transactionStatus = $status->transaction_status;
            
            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                // Payment successful
                $this->updateOrderToPaid($actualOrderId, $orderId);
                
                // Clear cart (updateOrderToPaid already clears cart, but ensure it's cleared in user session)
                $customerId = session()->get('customer_id');
                if ($customerId) {
                    $this->clearCart($customerId);
                }
                
                return redirect()->to('/checkout/success/' . $actualOrderId)->with('success', 'Pembayaran berhasil');
            } else if ($transactionStatus == 'pending') {
                return redirect()->to('/payment/process/' . $actualOrderId)->with('info', 'Pembayaran sedang diproses');
            } else {
                return redirect()->to('/payment/process/' . $actualOrderId)->with('error', 'Pembayaran gagal atau dibatalkan');
            }
        } catch (\Exception $e) {
            log_message('error', 'Midtrans Finish Error: ' . $e->getMessage());
            return redirect()->to('/payment/process/' . $actualOrderId)->with('error', 'Gagal memverifikasi status pembayaran');
        }
    }

    /**
     * Handle payment unfinish (redirect from Midtrans)
     */
    public function unfinish()
    {
        $orderId = $this->request->getGet('order_id');
        
        if (!$orderId) {
            return redirect()->to('/shop')->with('error', 'Order ID tidak ditemukan');
        }
        
        preg_match('/ORDER-(\d+)-/', $orderId, $matches);
        $actualOrderId = $matches[1] ?? null;
        
        if (!$actualOrderId) {
            return redirect()->to('/shop')->with('error', 'Format Order ID tidak valid');
        }
        
        return redirect()->to('/payment/process/' . $actualOrderId)->with('info', 'Pembayaran belum selesai. Silakan selesaikan pembayaran Anda.');
    }

    /**
     * Handle payment error (redirect from Midtrans)
     */
    public function error()
    {
        $orderId = $this->request->getGet('order_id');
        
        if (!$orderId) {
            return redirect()->to('/shop')->with('error', 'Order ID tidak ditemukan');
        }
        
        preg_match('/ORDER-(\d+)-/', $orderId, $matches);
        $actualOrderId = $matches[1] ?? null;
        
        if (!$actualOrderId) {
            return redirect()->to('/shop')->with('error', 'Format Order ID tidak valid');
        }
        
        return redirect()->to('/payment/process/' . $actualOrderId)->with('error', 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
    }
}

