<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RoleModel;
use App\Models\UserRoleModel;

class RoleController extends BaseController
{
    protected $roleModel;
    protected $userRoleModel;

    public function __construct()
    {
        $this->roleModel = new RoleModel();
        $this->userRoleModel = new UserRoleModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Kelola Role',
            'roles' => $this->roleModel->findAll(),
        ];

        return view('admin/roles/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Role',
        ];

        return view('admin/roles/create', $data);
    }

    public function store()
    {
        $rules = [
            'role_name' => 'required|min_length[2]|max_length[100]|is_unique[roles.role_name]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $roleData = [
            'role_name' => strtolower($this->request->getPost('role_name')),
        ];

        if ($this->roleModel->insert($roleData)) {
            return redirect()->to('/admin/roles')->with('success', 'Role berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan role');
    }

    public function edit($id)
    {
        $role = $this->roleModel->find($id);
        
        if (!$role) {
            return redirect()->to('/admin/roles')->with('error', 'Role tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Role',
            'role'  => $role,
        ];

        return view('admin/roles/edit', $data);
    }

    public function update($id)
    {
        $role = $this->roleModel->find($id);
        
        if (!$role) {
            return redirect()->to('/admin/roles')->with('error', 'Role tidak ditemukan');
        }

        $rules = [
            'role_name' => 'required|min_length[2]|max_length[100]|is_unique[roles.role_name,id,' . $id . ']',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $roleData = [
            'role_name' => strtolower($this->request->getPost('role_name')),
        ];

        if ($this->roleModel->update($id, $roleData)) {
            return redirect()->to('/admin/roles')->with('success', 'Role berhasil diperbarui');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui role');
    }

    public function delete($id)
    {
        $role = $this->roleModel->find($id);
        
        if (!$role) {
            return redirect()->to('/admin/roles')->with('error', 'Role tidak ditemukan');
        }

        // Check if role is being used
        $usersWithRole = $this->userRoleModel->where('role_id', $id)->countAllResults();
        
        if ($usersWithRole > 0) {
            return redirect()->to('/admin/roles')->with('error', 'Role tidak dapat dihapus karena masih digunakan oleh ' . $usersWithRole . ' pengguna');
        }

        if ($this->roleModel->delete($id)) {
            return redirect()->to('/admin/roles')->with('success', 'Role berhasil dihapus');
        }

        return redirect()->to('/admin/roles')->with('error', 'Gagal menghapus role');
    }
}

