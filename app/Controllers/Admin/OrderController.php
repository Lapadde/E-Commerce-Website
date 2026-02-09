<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
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
        $data = [
            'title'  => 'Kelola Pesanan',
            'orders' => $this->orderModel->getOrdersWithUser(),
        ];

        return view('admin/orders/index', $data);
    }

    public function show($id)
    {
        $order = $this->orderModel->select('orders.*, users.full_name as customer_name, users.email as customer_email, users.phone as customer_phone')
                                   ->join('users', 'users.id = orders.customer_id')
                                   ->where('orders.id', $id)
                                   ->first();
        
        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Pesanan #' . $order['id'],
            'order' => $order,
            'items' => $this->orderDetailModel->getItemsByOrder($id),
        ];

        return view('admin/orders/show', $data);
    }

    public function updateStatus($id)
    {
        $order = $this->orderModel->find($id);
        
        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan');
        }

        $status = $this->request->getPost('order_status');
        
        $this->orderModel->update($id, ['order_status' => $status]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui');
    }
}

