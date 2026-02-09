<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\ProductCategoryModel;

class ShopController extends BaseController
{
    protected $productModel;
    protected $categoryModel;
    protected $productCategoryModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->productCategoryModel = new ProductCategoryModel();
    }

    public function index()
    {
        // Get search and category filter
        $search = $this->request->getGet('search');
        $categoryId = $this->request->getGet('category');
        
        // Pagination
        $perPage = 12;
        $page = $this->request->getGet('page') ?? 1;
        
        // Build query
        $builder = $this->productModel->select('products.*')
                                     ->where('products.stock >', 0);
        
        // Add search filter
        if ($search) {
            $builder->groupStart()
                   ->like('products.name', $search)
                   ->orLike('products.description', $search)
                   ->orLike('products.sku', $search)
                   ->groupEnd();
        }
        
        // Add category filter
        if ($categoryId) {
            $builder->join('product_categories', 'product_categories.product_id = products.id')
                   ->where('product_categories.category_id', $categoryId)
                   ->groupBy('products.id');
        }
        
        $offset = ($page - 1) * $perPage;
        $products = $builder->orderBy('products.created_at', 'DESC')
                           ->limit($perPage, $offset)
                           ->findAll();
        
        // Get total count
        $totalBuilder = $this->productModel->select('products.id')
                                          ->where('products.stock >', 0);
        
        if ($search) {
            $totalBuilder->groupStart()
                        ->like('products.name', $search)
                        ->orLike('products.description', $search)
                        ->orLike('products.sku', $search)
                        ->groupEnd();
        }
        
        if ($categoryId) {
            $totalBuilder->join('product_categories', 'product_categories.product_id = products.id')
                        ->where('product_categories.category_id', $categoryId)
                        ->groupBy('products.id');
        }
        
        $total = $totalBuilder->countAllResults();
        
        // Get categories for filter
        $categories = $this->categoryModel->findAll();
        
        // Get cart count
        $cart = session()->get('cart') ?? [];
        $cartCount = array_sum(array_column($cart, 'quantity'));
        
        $data = [
            'title'     => 'Toko Online',
            'products'  => $products,
            'categories' => $categories,
            'search'    => $search,
            'categoryId' => $categoryId,
            'total'     => $total,
            'page'      => $page,
            'perPage'   => $perPage,
            'cartCount' => $cartCount,
        ];

        return view('shop/index', $data);
    }

    public function show($id)
    {
        $product = $this->productModel->find($id);
        
        if (!$product) {
            return redirect()->to('/shop')->with('error', 'Produk tidak ditemukan');
        }
        
        if ($product['stock'] <= 0) {
            return redirect()->to('/shop')->with('error', 'Produk sedang tidak tersedia');
        }
        
        // Get product categories
        $product['categories'] = $this->productModel->getProductCategories($id);
        
        // Get related products (same category)
        $relatedProducts = [];
        if (!empty($product['categories'])) {
            $categoryIds = array_column($product['categories'], 'id');
            $relatedProducts = $this->productModel->select('products.*')
                                                  ->join('product_categories', 'product_categories.product_id = products.id')
                                                  ->whereIn('product_categories.category_id', $categoryIds)
                                                  ->where('products.id !=', $id)
                                                  ->where('products.stock >', 0)
                                                  ->groupBy('products.id')
                                                  ->limit(4)
                                                  ->findAll();
        }
        
        // Get cart count
        $cart = session()->get('cart') ?? [];
        $cartCount = array_sum(array_column($cart, 'quantity'));
        
        $data = [
            'title'           => $product['name'],
            'product'         => $product,
            'relatedProducts' => $relatedProducts,
            'cartCount'       => $cartCount,
        ];

        return view('shop/detail', $data);
    }

    /**
     * API endpoint for real-time search
     */
    public function search()
    {
        // Get search and category filter
        $search = $this->request->getGet('q');
        $categoryId = $this->request->getGet('category');
        
        // Pagination
        $perPage = 12;
        $page = $this->request->getGet('page') ?? 1;
        
        // Build query
        $builder = $this->productModel->select('products.*')
                                     ->where('products.stock >', 0);
        
        // Add search filter
        if ($search) {
            $builder->groupStart()
                   ->like('products.name', $search)
                   ->orLike('products.description', $search)
                   ->orLike('products.sku', $search)
                   ->groupEnd();
        }
        
        // Add category filter
        if ($categoryId) {
            $builder->join('product_categories', 'product_categories.product_id = products.id')
                   ->where('product_categories.category_id', $categoryId)
                   ->groupBy('products.id');
        }
        
        $offset = ($page - 1) * $perPage;
        $products = $builder->orderBy('products.created_at', 'DESC')
                           ->limit($perPage, $offset)
                           ->findAll();
        
        // Get total count
        $totalBuilder = $this->productModel->select('products.id')
                                          ->where('products.stock >', 0);
        
        if ($search) {
            $totalBuilder->groupStart()
                        ->like('products.name', $search)
                        ->orLike('products.description', $search)
                        ->orLike('products.sku', $search)
                        ->groupEnd();
        }
        
        if ($categoryId) {
            $totalBuilder->join('product_categories', 'product_categories.product_id = products.id')
                        ->where('product_categories.category_id', $categoryId)
                        ->groupBy('products.id');
        }
        
        $total = $totalBuilder->countAllResults();
        
        // Return JSON response
        return $this->response->setJSON([
            'success' => true,
            'products' => $products,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => ceil($total / $perPage),
        ]);
    }
}

