<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\UserRoleModel;
use App\Models\AuditLogModel;

class CustomerController extends BaseController
{
    protected $userModel;
    protected $roleModel;
    protected $userRoleModel;
    protected $auditLogModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        $this->userRoleModel = new UserRoleModel();
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
        
        // Get customer role ID
        $customerRole = $this->roleModel->where('role_name', 'customer')->first();
        
        if (!$customerRole) {
            return redirect()->to('/admin/dashboard')->with('error', 'Role customer tidak ditemukan');
        }
        
        // Build query with search and pagination
        $customers = $this->userModel->getCustomersPaginated($customerRole['id'], $search, $perPage, $page);
        $total = $this->userModel->getCustomersCount($customerRole['id'], $search);
        
        // Create pagination
        $pager = \Config\Services::pager();
        $pager->store('customers', $page, $perPage, $total);
        
        $data = [
            'title'    => 'Kelola Pelanggan',
            'customers' => $customers,
            'pager'    => $pager,
            'search'   => $search,
            'total'    => $total,
        ];

        return view('admin/customers/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Pelanggan',
        ];

        return view('admin/customers/create', $data);
    }

    public function store()
    {
        $rules = [
            'full_name' => 'required|min_length[3]|max_length[255]',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'password'  => 'required|min_length[6]',
            'phone'     => 'permit_empty|max_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get customer role
        $customerRole = $this->roleModel->where('role_name', 'customer')->first();
        
        if (!$customerRole) {
            return redirect()->back()->withInput()->with('error', 'Role customer tidak ditemukan');
        }

        $userData = [
            'full_name' => $this->request->getPost('full_name'),
            'email'     => $this->request->getPost('email'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'phone'     => $this->request->getPost('phone') ?: null,
        ];

        $userId = $this->userModel->insert($userData);
        
        if (!$userId) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan pelanggan');
        }

        // Assign customer role
        $this->userRoleModel->assignRole($userId, $customerRole['id']);

        // Log activity
        $currentUserId = $this->getCurrentUserId();
        if ($currentUserId) {
            $this->auditLogModel->logAction($currentUserId, "Menambah pelanggan: {$userData['full_name']} (Email: {$userData['email']})");
        }

        return redirect()->to('/admin/customers')->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function edit($id)
    {
        // Get customer role
        $customerRole = $this->roleModel->where('role_name', 'customer')->first();
        
        // Verify user is customer
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/customers')->with('error', 'Pelanggan tidak ditemukan');
        }
        
        $userRoles = $this->userModel->getUserRoles($id);
        $isCustomer = false;
        foreach ($userRoles as $role) {
            if ($role['role_name'] == 'customer') {
                $isCustomer = true;
                break;
            }
        }
        
        if (!$isCustomer) {
            return redirect()->to('/admin/customers')->with('error', 'User ini bukan pelanggan');
        }

        $data = [
            'title' => 'Edit Pelanggan',
            'customer'  => $user,
        ];

        return view('admin/customers/edit', $data);
    }

    public function update($id)
    {
        // Verify user is customer
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/customers')->with('error', 'Pelanggan tidak ditemukan');
        }

        $rules = [
            'full_name' => 'required|min_length[3]|max_length[255]',
            'email'     => 'required|valid_email|is_unique[users.email,id,' . $id . ']',
            'phone'     => 'permit_empty|max_length[20]',
        ];

        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userData = [
            'full_name' => $this->request->getPost('full_name'),
            'email'     => $this->request->getPost('email'),
            'phone'     => $this->request->getPost('phone') ?: null,
        ];

        if ($this->request->getPost('password')) {
            $userData['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $userData);

        // Log activity
        $currentUserId = $this->getCurrentUserId();
        if ($currentUserId) {
            $this->auditLogModel->logAction($currentUserId, "Mengupdate pelanggan: {$userData['full_name']} (ID: {$id})");
        }

        return redirect()->to('/admin/customers')->with('success', 'Pelanggan berhasil diperbarui');
    }

    public function delete($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/admin/customers')->with('error', 'Pelanggan tidak ditemukan');
        }

        // Prevent deleting self
        if ($user['id'] == session()->get('admin_id')) {
            return redirect()->to('/admin/customers')->with('error', 'Anda tidak dapat menghapus akun sendiri');
        }

        // Log activity before delete
        $currentUserId = $this->getCurrentUserId();
        if ($currentUserId) {
            $this->auditLogModel->logAction($currentUserId, "Menghapus pelanggan: {$user['full_name']} (ID: {$id})");
        }

        // Delete user roles
        $this->userRoleModel->where('user_id', $id)->delete();

        $this->userModel->delete($id);

        return redirect()->to('/admin/customers')->with('success', 'Pelanggan berhasil dihapus');
    }
}

