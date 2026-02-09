<?php

namespace App\Controllers;

use App\Models\ProductModel;

class CartController extends BaseController
{
    protected $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    private function getCartKey()
    {
        $customerId = session()->get('customer_id');
        if (!$customerId) {
            // This should not happen as cart routes are protected by customerAuth filter
            // But just in case, return null to prevent accessing old cart
            return null;
        }
        return 'cart_' . $customerId;
    }

    public function index()
    {
        $cartKey = $this->getCartKey();
        if (!$cartKey) {
            $cart = [];
        } else {
            $cart = session()->get($cartKey) ?? [];
        }
        $cartItems = [];
        $total = 0;
        
        foreach ($cart as $productId => $item) {
            $product = $this->productModel->find($productId);
            if ($product) {
                $item['product'] = $product;
                $item['subtotal'] = $product['price'] * $item['quantity'];
                $total += $item['subtotal'];
                $cartItems[] = $item;
            }
        }
        
        $data = [
            'title'     => 'Keranjang Belanja',
            'cartItems' => $cartItems,
            'total'     => $total,
            'cartCount' => array_sum(array_column($cart, 'quantity')),
        ];

        return view('shop/cart', $data);
    }

    public function add()
    {
        $productId = $this->request->getPost('product_id');
        $quantity = (int) $this->request->getPost('quantity') ?? 1;
        
        if (!$productId) {
            return redirect()->back()->with('error', 'Produk tidak valid');
        }
        
        $product = $this->productModel->find($productId);
        
        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan');
        }
        
        if ($product['stock'] <= 0) {
            return redirect()->back()->with('error', 'Produk sedang tidak tersedia');
        }
        
        $cartKey = $this->getCartKey();
        if (!$cartKey) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        $cart = session()->get($cartKey) ?? [];
        
        // Check if product already in cart
        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $quantity;
            
            if ($newQuantity > $product['stock']) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $product['stock']);
            }
            
            $cart[$productId]['quantity'] = $newQuantity;
        } else {
            if ($quantity > $product['stock']) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $product['stock']);
            }
            
            $cart[$productId] = [
                'product_id' => $productId,
                'quantity'   => $quantity,
            ];
        }
        
        session()->set($cartKey, $cart);
        
        return redirect()->to('/cart')->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function update()
    {
        $productId = $this->request->getPost('product_id');
        $quantity = (int) $this->request->getPost('quantity');
        
        if (!$productId || $quantity < 1) {
            return redirect()->back()->with('error', 'Jumlah tidak valid');
        }
        
        $product = $this->productModel->find($productId);
        
        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan');
        }
        
        if ($quantity > $product['stock']) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $product['stock']);
        }
        
        $cartKey = $this->getCartKey();
        if (!$cartKey) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        $cart = session()->get($cartKey) ?? [];
        
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            session()->set($cartKey, $cart);
        }
        
        // Redirect without success message to avoid showing SweetAlert on quantity update
        return redirect()->to('/cart');
    }

    public function remove($productId)
    {
        $cartKey = $this->getCartKey();
        if (!$cartKey) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        $cart = session()->get($cartKey) ?? [];
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->set($cartKey, $cart);
        }
        
        return redirect()->to('/cart')->with('success', 'Produk berhasil dihapus dari keranjang');
    }

    public function clear()
    {
        $cartKey = $this->getCartKey();
        if ($cartKey) {
            session()->remove($cartKey);
        }
        return redirect()->to('/cart')->with('success', 'Keranjang berhasil dikosongkan');
    }
}

