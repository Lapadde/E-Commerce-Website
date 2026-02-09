<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'customer_id', 'order_date', 'amount', 'shipping_address', 'order_status',
        'payment_status', 'payment_method', 'snap_token', 'transaction_id'
    ];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'customer_id'      => 'required|integer',
        'order_date'       => 'required',
        'amount'           => 'required|decimal',
        'shipping_address' => 'required',
        'order_status'     => 'required',
    ];
    
    protected $validationMessages = [];

    protected $skipValidation = false;

    public function getOrdersWithUser()
    {
        return $this->select('orders.*, users.full_name as customer_name, users.email as customer_email')
                    ->join('users', 'users.id = orders.customer_id')
                    ->orderBy('orders.order_date', 'DESC')
                    ->findAll();
    }

    public function getOrdersByStatus($status)
    {
        return $this->where('order_status', $status)->findAll();
    }

    public function getTotalRevenue()
    {
        $result = $this->selectSum('amount')
                      ->where('payment_status', 'paid')
                      ->first();
        return $result['amount'] ?? 0;
    }

    public function getMonthlyRevenue($month = null, $year = null)
    {
        $month = $month ?? date('m');
        $year = $year ?? date('Y');
        
        $result = $this->selectSum('amount')
                      ->where('payment_status', 'paid')
                      ->where('MONTH(order_date)', $month)
                      ->where('YEAR(order_date)', $year)
                      ->first();
        return $result['amount'] ?? 0;
    }

    public function getOrdersByCustomer($customerId)
    {
        return $this->where('customer_id', $customerId)
                    ->orderBy('order_date', 'DESC')
                    ->findAll();
    }

    public function getOrderWithDetails($orderId, $customerId = null)
    {
        $builder = $this->select('orders.*, users.full_name as customer_name, users.email as customer_email, users.phone as customer_phone')
                       ->join('users', 'users.id = orders.customer_id')
                       ->where('orders.id', $orderId);
        
        if ($customerId) {
            $builder->where('orders.customer_id', $customerId);
        }
        
        return $builder->first();
    }
}

