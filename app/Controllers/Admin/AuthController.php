<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\AuditLogModel;

class AuthController extends BaseController
{
    protected $userModel;
    protected $auditLogModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->auditLogModel = new AuditLogModel();
    }

    public function login()
    {
        if (session()->get('admin_logged_in')) {
            return redirect()->to('/admin/dashboard');
        }
        
        return view('admin/auth/login');
    }

    public function attemptLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Email tidak ditemukan');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Password salah');
        }

        // Get user roles
        $userRoles = $this->userModel->getUserRoles($user['id']);
        
        // Check if user has at least one admin role (admin, manager, staff)
        $allowedRoles = ['admin', 'manager', 'staff'];
        $hasAccess = false;
        $userRoleNames = [];
        
        foreach ($userRoles as $role) {
            $roleName = strtolower($role['role_name']);
            $userRoleNames[] = $role['role_name'];
            if (in_array($roleName, $allowedRoles)) {
                $hasAccess = true;
            }
        }

        if (!$hasAccess) {
            return redirect()->back()->withInput()->with('error', 'Anda tidak memiliki akses admin');
        }

        // Set session with roles
        session()->set([
            'admin_id'        => $user['id'],
            'admin_name'      => $user['full_name'],
            'admin_email'     => $user['email'],
            'admin_roles'     => $userRoleNames,
            'admin_logged_in' => true,
        ]);

        // Log activity
        $roleName = !empty($userRoleNames) ? implode(', ', $userRoleNames) : 'Unknown';
        $this->auditLogModel->logAction($user['id'], "Login ke Admin Panel (Role: {$roleName})");

        return redirect()->to('/admin/dashboard')->with('success', 'Selamat datang, ' . $user['full_name']);
    }

    public function logout()
    {
        // Log activity before logout
        $adminId = session()->get('admin_id');
        if ($adminId) {
            $this->auditLogModel->logAction($adminId, 'Logout dari Admin Panel');
        }
        
        session()->destroy();
        return redirect()->to('/admin/login')->with('success', 'Anda telah berhasil logout');
    }
}

