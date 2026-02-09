<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'sku', 'name', 'price', 'stock', 'description', 'weight', 'image'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'sku'   => 'required|is_unique[products.sku,id,{id}]',
        'name'  => 'required|min_length[3]|max_length[255]',
        'price' => 'required|decimal',
        'stock' => 'required|integer',
    ];

    protected $validationMessages = [
        'sku' => [
            'required'  => 'SKU harus diisi',
            'is_unique' => 'SKU sudah digunakan',
        ],
        'name' => [
            'required'   => 'Nama produk harus diisi',
            'min_length' => 'Nama produk minimal 3 karakter',
        ],
        'price' => [
            'required' => 'Harga harus diisi',
            'decimal'  => 'Harga harus berupa angka',
        ],
        'stock' => [
            'required' => 'Stok harus diisi',
            'integer'  => 'Stok harus berupa angka',
        ],
    ];

    protected $skipValidation = false;

    public function getProductsWithCategories()
    {
        return $this->select('products.*, GROUP_CONCAT(categories.name) as category_names')
                    ->join('product_categories', 'product_categories.product_id = products.id', 'left')
                    ->join('categories', 'categories.id = product_categories.category_id', 'left')
                    ->groupBy('products.id')
                    ->findAll();
    }

    public function getProductsWithCategoriesPaginated($search = null, $perPage = 10, $page = 1)
    {
        $builder = $this->select('products.*, GROUP_CONCAT(categories.name) as category_names')
                       ->join('product_categories', 'product_categories.product_id = products.id', 'left')
                       ->join('categories', 'categories.id = product_categories.category_id', 'left')
                       ->groupBy('products.id');
        
        // Add search filter
        if ($search) {
            $builder->groupStart()
                   ->like('products.name', $search)
                   ->orLike('products.sku', $search)
                   ->orLike('products.description', $search)
                   ->groupEnd();
        }
        
        $offset = ($page - 1) * $perPage;
        return $builder->orderBy('products.created_at', 'DESC')
                     ->limit($perPage, $offset)
                     ->findAll();
    }

    public function getProductsCount($search = null)
    {
        $builder = $this->select('products.id')
                       ->join('product_categories', 'product_categories.product_id = products.id', 'left')
                       ->join('categories', 'categories.id = product_categories.category_id', 'left')
                       ->groupBy('products.id');
        
        // Add search filter
        if ($search) {
            $builder->groupStart()
                   ->like('products.name', $search)
                   ->orLike('products.sku', $search)
                   ->orLike('products.description', $search)
                   ->groupEnd();
        }
        
        return $builder->countAllResults();
    }

    public function getProductCategories($productId)
    {
        return $this->db->table('product_categories')
                       ->select('categories.*')
                       ->join('categories', 'categories.id = product_categories.category_id')
                       ->where('product_categories.product_id', $productId)
                       ->get()
                       ->getResultArray();
    }

    public function getProductsByCategory($categoryId)
    {
        return $this->select('products.*')
                    ->join('product_categories', 'product_categories.product_id = products.id')
                    ->where('product_categories.category_id', $categoryId)
                    ->findAll();
    }

    public function getLowStockProducts($threshold = 10)
    {
        return $this->where('stock <', $threshold)->findAll();
    }
}

