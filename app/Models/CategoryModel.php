<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name', 'description'
    ];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[255]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'   => 'Nama kategori harus diisi',
            'min_length' => 'Nama kategori minimal 2 karakter',
        ],
    ];

    protected $skipValidation = false;

    public function getCategoryWithProducts($id)
    {
        return $this->select('categories.*, COUNT(DISTINCT products.id) as total_products')
                    ->join('product_categories', 'product_categories.category_id = categories.id', 'left')
                    ->join('products', 'products.id = product_categories.product_id', 'left')
                    ->where('categories.id', $id)
                    ->groupBy('categories.id')
                    ->first();
    }

    public function getCategoryProducts($categoryId)
    {
        return $this->db->table('products')
                       ->select('products.*')
                       ->join('product_categories', 'product_categories.product_id = products.id')
                       ->where('product_categories.category_id', $categoryId)
                       ->get()
                       ->getResultArray();
    }

    public function getActiveCategories()
    {
        return $this->orderBy('name', 'ASC')->findAll();
    }
}

