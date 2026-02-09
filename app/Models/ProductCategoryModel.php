<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductCategoryModel extends Model
{
    protected $table            = 'product_categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'product_id', 'category_id'
    ];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'product_id'  => 'required|integer',
        'category_id' => 'required|integer',
    ];

    protected $skipValidation = false;

    public function assignCategory($productId, $categoryId)
    {
        // Check if already assigned
        $existing = $this->where(['product_id' => $productId, 'category_id' => $categoryId])->first();
        if ($existing) {
            return false;
        }

        return $this->insert([
            'product_id'  => $productId,
            'category_id' => $categoryId,
        ]);
    }

    public function removeCategory($productId, $categoryId)
    {
        return $this->where(['product_id' => $productId, 'category_id' => $categoryId])->delete();
    }

    public function getProductCategories($productId)
    {
        return $this->select('categories.*')
                   ->join('categories', 'categories.id = product_categories.category_id')
                   ->where('product_categories.product_id', $productId)
                   ->findAll();
    }
}

