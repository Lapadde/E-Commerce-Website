<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\UserRoleModel;

class UserController extends BaseController
{
    protected $userModel;
    protected $roleModel;
    protected $userRoleModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        $this->userRoleModel = new UserRoleModel();
    }

    public function index()
    {
        $users = $this->userModel->findAll();
        
        // Get roles for each user
        foreach ($users as &$user) {
            $user['roles'] = $this->userModel->getUserRoles($user['id']);
        }

        $data = [
            'title' => 'Kelola Pengguna',
            'users' => $users,
        ];

        return view('admin/users/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Pengguna',
            'roles' => $this->roleModel->findAll(),
        ];

        return view('admin/users/create', $data);
    }

    public function store()
    {
        $rules = [
            'full_name' => 'required|min_length[3]',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'password'  => 'required|min_length[6]',
            'roles'     => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userData = [
            'full_name' => $this->request->getPost('full_name'),
            'email'     => $this->request->getPost('email'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'phone'     => $this->request->getPost('phone') ?: null,
        ];

        $userId = $this->userModel->insert($userData);
        
        if (!$userId) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan pengguna');
        }

        // Assign roles
        $roles = $this->request->getPost('roles');
        if (is_array($roles)) {
            foreach ($roles as $roleId) {
                $this->userRoleModel->assignRole($userId, $roleId);
            }
        }

        return redirect()->to('/admin/users')->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan');
        }

        $user['roles'] = $this->userModel->getUserRoles($id);
        $user['role_ids'] = array_column($user['roles'], 'id');

        $data = [
            'title' => 'Edit Pengguna',
            'user'  => $user,
            'roles' => $this->roleModel->findAll(),
        ];

        return view('admin/users/edit', $data);
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan');
        }

        $rules = [
            'full_name' => 'required|min_length[3]',
            'email'     => 'required|valid_email|is_unique[users.email,id,' . $id . ']',
            'roles'     => 'required',
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

        // Update roles
        $roles = $this->request->getPost('roles');
        if (is_array($roles)) {
            // Remove all existing roles
            $this->userRoleModel->where('user_id', $id)->delete();
            
            // Assign new roles
            foreach ($roles as $roleId) {
                $this->userRoleModel->assignRole($id, $roleId);
            }
        }

        return redirect()->to('/admin/users')->with('success', 'Pengguna berhasil diperbarui');
    }

    public function delete($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan');
        }

        // Prevent deleting self
        if ($user['id'] == session()->get('admin_id')) {
            return redirect()->to('/admin/users')->with('error', 'Anda tidak dapat menghapus akun sendiri');
        }

        // Delete user roles
        $this->userRoleModel->where('user_id', $id)->delete();

        $this->userModel->delete($id);

        return redirect()->to('/admin/users')->with('success', 'Pengguna berhasil dihapus');
    }
}

