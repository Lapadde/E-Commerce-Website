<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderDetailModel;

class OrderController extends BaseController
{
    protected $orderModel;
    protected $orderDetailModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderDetailModel = new OrderDetailModel();
    }

    public function index()
    {
        $customerId = session()->get('customer_id');
        
        if (!$customerId) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $orders = $this->orderModel->getOrdersByCustomer($customerId);

        $data = [
            'title' => 'Riwayat Pesanan',
            'orders' => $orders,
        ];

        return view('shop/orders/index', $data);
    }

    public function show($orderId)
    {
        $customerId = session()->get('customer_id');
        
        if (!$customerId) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $order = $this->orderModel->getOrderWithDetails($orderId, $customerId);
        
        if (!$order) {
            return redirect()->to('/orders')->with('error', 'Pesanan tidak ditemukan');
        }

        $orderItems = $this->orderDetailModel->getItemsByOrder($orderId);

        $data = [
            'title' => 'Detail Pesanan #' . $orderId,
            'order' => $order,
            'orderItems' => $orderItems,
        ];

        return view('shop/orders/show', $data);
    }
}

