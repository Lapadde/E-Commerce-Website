<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\OrderModel;

class DashboardController extends BaseController
{
    protected $userModel;
    protected $productModel;
    protected $categoryModel;
    protected $orderModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->orderModel = new OrderModel();
    }

    public function index()
    {
        $data = [
            'title'           => 'Dashboard',
            'totalUsers'      => $this->userModel->countAll(),
            'totalProducts'   => $this->productModel->countAll(),
            'totalCategories' => $this->categoryModel->countAll(),
            'totalOrders'     => $this->orderModel->countAll(),
            'totalRevenue'    => $this->orderModel->getTotalRevenue(),
            'monthlyRevenue'  => $this->orderModel->getMonthlyRevenue(),
            'recentOrders'    => $this->orderModel->getOrdersWithUser(),
            'lowStockProducts'=> $this->productModel->getLowStockProducts(),
            'pendingOrders'   => $this->orderModel->where('order_status', 'pending')->countAllResults(),
        ];

        return view('admin/dashboard/index', $data);
    }
}

