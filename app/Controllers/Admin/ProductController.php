<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\ProductCategoryModel;
use App\Models\AuditLogModel;

class ProductController extends BaseController
{
    protected $productModel;
    protected $categoryModel;
    protected $productCategoryModel;
    protected $auditLogModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->productCategoryModel = new ProductCategoryModel();
        $this->auditLogModel = new AuditLogModel();
    }

    private function getCurrentUserId()
    {
        return session()->get('admin_id') ?? session()->get('customer_id');
    }

    public function index()
    {
        // Get search parameter
        $search = $this->request->getGet('search');
        
        // Pagination
        $perPage = 10;
        $page = $this->request->getGet('page') ?? 1;
        
        // Build query with search
        $products = $this->productModel->getProductsWithCategoriesPaginated($search, $perPage, $page);
        $total = $this->productModel->getProductsCount($search);
        
        // Create pagination
        $pager = \Config\Services::pager();
        $pager->store('products', $page, $perPage, $total);
        
        $data = [
            'title'    => 'Kelola Produk',
            'products' => $products,
            'pager'    => $pager,
            'search'   => $search,
            'total'    => $total,
        ];

        return view('admin/products/index', $data);
    }

    public function create()
    {
        $data = [
            'title'      => 'Tambah Produk',
            'categories' => $this->categoryModel->getActiveCategories(),
        ];

        return view('admin/products/create', $data);
    }

    public function store()
    {
        $rules = [
            'sku'   => 'required|is_unique[products.sku]',
            'name'  => 'required|min_length[3]',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'categories' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Handle image upload
        $image = $this->request->getFile('image');
        $imageName = null;
        
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move('uploads/products', $imageName);
        }

        // Get and sanitize price value
        $price = $this->request->getPost('price');
        // Remove any non-numeric characters except decimal point
        $price = preg_replace('/[^0-9.]/', '', $price);
        // Convert to float to ensure proper decimal format
        $price = (float) $price;

        $data = [
            'sku'         => $this->request->getPost('sku'),
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price'       => $price,
            'stock'       => (int) $this->request->getPost('stock'),
            'weight'      => $this->request->getPost('weight') ? (float) $this->request->getPost('weight') : null,
            'image'       => $imageName,
        ];

        $productId = $this->productModel->insert($data);
        
        if (!$productId) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan produk');
        }

        // Assign categories
        $categories = $this->request->getPost('categories');
        if (is_array($categories)) {
            foreach ($categories as $categoryId) {
                $this->productCategoryModel->assignCategory($productId, $categoryId);
            }
        }

        // Log activity
        $userId = $this->getCurrentUserId();
        if ($userId) {
            $this->auditLogModel->logAction($userId, "Menambah produk: {$data['name']} (SKU: {$data['sku']})");
        }

        return redirect()->to('/admin/products')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $product = $this->productModel->find($id);
        
        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Produk tidak ditemukan');
        }

        $product['categories'] = $this->productModel->getProductCategories($id);
        $product['category_ids'] = array_column($product['categories'], 'id');

        $data = [
            'title'      => 'Edit Produk',
            'product'    => $product,
            'categories' => $this->categoryModel->findAll(),
        ];

        return view('admin/products/edit', $data);
    }

    public function update($id)
    {
        $product = $this->productModel->find($id);
        
        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Produk tidak ditemukan');
        }

        $rules = [
            'sku'   => 'required|is_unique[products.sku,id,' . $id . ']',
            'name'  => 'required|min_length[3]',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'categories' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Handle image upload
        $image = $this->request->getFile('image');
        $imageName = $product['image'];
        
        if ($image && $image->isValid() && !$image->hasMoved()) {
            // Delete old image
            if ($product['image'] && file_exists('uploads/products/' . $product['image'])) {
                unlink('uploads/products/' . $product['image']);
            }
            $imageName = $image->getRandomName();
            $image->move('uploads/products', $imageName);
        }

        // Get and sanitize price value
        $price = $this->request->getPost('price');
        // Remove any non-numeric characters except decimal point
        $price = preg_replace('/[^0-9.]/', '', $price);
        // Convert to float to ensure proper decimal format
        $price = (float) $price;

        $data = [
            'sku'         => $this->request->getPost('sku'),
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price'       => $price,
            'stock'       => (int) $this->request->getPost('stock'),
            'weight'      => $this->request->getPost('weight') ? (float) $this->request->getPost('weight') : null,
            'image'       => $imageName,
        ];

        // Use skipValidation to ensure update works even if model validation fails
        $result = $this->productModel->skipValidation(true)->update($id, $data);
        
        if (!$result) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui produk. Silakan coba lagi.');
        }

        // Update categories
        $categories = $this->request->getPost('categories');
        if (is_array($categories)) {
            // Remove all existing categories
            $this->productCategoryModel->where('product_id', $id)->delete();
            
            // Assign new categories
            foreach ($categories as $categoryId) {
                $this->productCategoryModel->assignCategory($id, $categoryId);
            }
        }

        // Log activity
        $userId = $this->getCurrentUserId();
        if ($userId) {
            $this->auditLogModel->logAction($userId, "Mengupdate produk: {$data['name']} (ID: {$id})");
        }

        return redirect()->to('/admin/products')->with('success', 'Produk berhasil diperbarui');
    }

    public function delete($id)
    {
        $product = $this->productModel->find($id);
        
        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Produk tidak ditemukan');
        }

        // Delete image
        if ($product['image'] && file_exists('uploads/products/' . $product['image'])) {
            unlink('uploads/products/' . $product['image']);
        }

        // Log activity before delete
        $userId = $this->getCurrentUserId();
        if ($userId) {
            $this->auditLogModel->logAction($userId, "Menghapus produk: {$product['name']} (ID: {$id})");
        }

        $this->productModel->delete($id);

        return redirect()->to('/admin/products')->with('success', 'Produk berhasil dihapus');
    }
}

