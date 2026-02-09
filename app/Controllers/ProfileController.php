<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AuditLogModel;

class ProfileController extends BaseController
{
    protected $userModel;
    protected $auditLogModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->auditLogModel = new AuditLogModel();
    }

    /**
     * Display profile page
     */
    public function index()
    {
        $customerId = session()->get('customer_id');
        
        if (!$customerId) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $customer = $this->userModel->find($customerId);
        
        if (!$customer) {
            return redirect()->to('/shop')->with('error', 'Data customer tidak ditemukan');
        }

        $data = [
            'title' => 'Profil Saya',
            'customer' => $customer,
        ];

        return view('shop/profile/index', $data);
    }

    /**
     * Update profile information
     */
    public function update()
    {
        $customerId = session()->get('customer_id');
        
        if (!$customerId) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $customer = $this->userModel->find($customerId);
        
        if (!$customer) {
            return redirect()->to('/shop')->with('error', 'Data customer tidak ditemukan');
        }

        $rules = [
            'full_name' => 'required|min_length[3]|max_length[255]',
            'email'     => 'required|valid_email|is_unique[users.email,id,' . $customerId . ']',
            'phone'     => 'permit_empty|max_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'full_name' => $this->request->getPost('full_name'),
            'email'     => $this->request->getPost('email'),
            'phone'     => $this->request->getPost('phone') ?? null,
        ];

        if ($this->userModel->update($customerId, $updateData)) {
            // Update session
            session()->set([
                'customer_name'  => $updateData['full_name'],
                'customer_email' => $updateData['email'],
            ]);

            // Log activity
            $this->auditLogModel->logAction($customerId, 'Mengupdate profil');

            return redirect()->to('/profile')->with('success', 'Profil berhasil diperbarui');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui profil');
        }
    }

    /**
     * Display change password page
     */
    public function changePassword()
    {
        $customerId = session()->get('customer_id');
        
        if (!$customerId) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $data = [
            'title' => 'Ubah Password',
        ];

        return view('shop/profile/change-password', $data);
    }

    /**
     * Process password change
     */
    public function updatePassword()
    {
        $customerId = session()->get('customer_id');
        
        if (!$customerId) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $customer = $this->userModel->find($customerId);
        
        if (!$customer) {
            return redirect()->to('/shop')->with('error', 'Data customer tidak ditemukan');
        }

        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');

        // Verify current password
        if (!password_verify($currentPassword, $customer['password'])) {
            return redirect()->back()->withInput()->with('error', 'Password saat ini tidak benar');
        }

        // Update password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        if ($this->userModel->update($customerId, ['password' => $hashedPassword])) {
            // Log activity
            $this->auditLogModel->logAction($customerId, 'Mengubah password');

            return redirect()->to('/profile')->with('success', 'Password berhasil diubah');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah password');
        }
    }
}

