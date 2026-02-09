<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\AuditLogModel;

class CategoryController extends BaseController
{
    protected $categoryModel;
    protected $auditLogModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->auditLogModel = new AuditLogModel();
    }

    private function getCurrentUserId()
    {
        return session()->get('admin_id') ?? session()->get('customer_id');
    }

    public function index()
    {
        $data = [
            'title'      => 'Kelola Kategori',
            'categories' => $this->categoryModel->findAll(),
        ];

        return view('admin/categories/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Kategori',
        ];

        return view('admin/categories/create', $data);
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[2]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ];

        $this->categoryModel->insert($data);

        // Log activity
        $userId = $this->getCurrentUserId();
        if ($userId) {
            $this->auditLogModel->logAction($userId, "Menambah kategori: {$data['name']}");
        }

        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit($id)
    {
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('/admin/categories')->with('error', 'Kategori tidak ditemukan');
        }

        $data = [
            'title'    => 'Edit Kategori',
            'category' => $category,
        ];

        return view('admin/categories/edit', $data);
    }

    public function update($id)
    {
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('/admin/categories')->with('error', 'Kategori tidak ditemukan');
        }

        $rules = [
            'name' => 'required|min_length[2]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ];

        $this->categoryModel->update($id, $data);

        // Log activity
        $userId = $this->getCurrentUserId();
        if ($userId) {
            $this->auditLogModel->logAction($userId, "Mengupdate kategori: {$data['name']} (ID: {$id})");
        }

        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil diperbarui');
    }

    public function delete($id)
    {
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('/admin/categories')->with('error', 'Kategori tidak ditemukan');
        }

        // Log activity before delete
        $userId = $this->getCurrentUserId();
        if ($userId) {
            $this->auditLogModel->logAction($userId, "Menghapus kategori: {$category['name']} (ID: {$id})");
        }

        $this->categoryModel->delete($id);

        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil dihapus');
    }
}

