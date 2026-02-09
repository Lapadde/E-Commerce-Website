<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderDetailModel extends Model
{
    protected $table            = 'order_details';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_id', 'product_id', 'price', 'quantity'
    ];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'order_id'   => 'required|integer',
        'product_id' => 'required|integer',
        'price'      => 'required|decimal',
        'quantity'   => 'required|integer',
    ];

    protected $skipValidation = false;

    public function getItemsByOrder($orderId)
    {
        return $this->select('order_details.*, products.name as product_name, products.image as product_image')
                    ->join('products', 'products.id = order_details.product_id', 'left')
                    ->where('order_id', $orderId)
                    ->findAll();
    }
}

