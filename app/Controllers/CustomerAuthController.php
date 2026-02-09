<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\UserRoleModel;
use App\Models\AuditLogModel;

class CustomerAuthController extends BaseController
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

    public function register()
    {
        // If already logged in, redirect to shop
        if (session()->get('customer_logged_in')) {
            return redirect()->to('/shop');
        }
        
        return view('auth/register');
    }

    public function attemptRegister()
    {
        $rules = [
            'full_name' => 'required|min_length[3]|max_length[255]',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'password'  => 'required|min_length[6]',
            'phone'     => 'permit_empty|max_length[20]',
            'confirm_password' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get customer role
        $customerRole = $this->roleModel->where('role_name', 'customer')->first();
        
        if (!$customerRole) {
            return redirect()->back()->withInput()->with('error', 'Sistem error. Silakan hubungi administrator');
        }

        $userData = [
            'full_name' => $this->request->getPost('full_name'),
            'email'     => $this->request->getPost('email'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'phone'     => $this->request->getPost('phone') ?: null,
        ];

        $userId = $this->userModel->insert($userData);
        
        if (!$userId) {
            return redirect()->back()->withInput()->with('error', 'Gagal mendaftar. Silakan coba lagi');
        }

        // Assign customer role
        $this->userRoleModel->assignRole($userId, $customerRole['id']);

        // Log activity
        $this->auditLogModel->logAction($userId, 'Registrasi akun customer baru');

        // Auto login after registration
        session()->set([
            'customer_id'        => $userId,
            'customer_name'       => $userData['full_name'],
            'customer_email'      => $userData['email'],
            'customer_logged_in'  => true,
        ]);

        // Handle pending cart item (if user tried to add to cart before registration)
        // IMPORTANT: Only process if pending_cart_item exists and user just registered
        // This prevents cross-user contamination if multiple users use the same browser/session
        $pendingCartItem = session()->get('pending_cart_item');
        if ($pendingCartItem && isset($pendingCartItem['product_id'])) {
            // Remove immediately to prevent reuse
            session()->remove('pending_cart_item');
            
            // Add product to cart using the newly registered user's ID
            $cartKey = 'cart_' . $userId;
            $cart = session()->get($cartKey) ?? [];
            
            $productModel = new \App\Models\ProductModel();
            $product = $productModel->find($pendingCartItem['product_id']);
            
            if ($product && $product['stock'] > 0) {
                $productId = $pendingCartItem['product_id'];
                $quantity = (int)($pendingCartItem['quantity'] ?? 1);
                
                // Validate quantity
                if ($quantity < 1) {
                    $quantity = 1;
                }
                
                if (isset($cart[$productId])) {
                    $newQuantity = $cart[$productId]['quantity'] + $quantity;
                    if ($newQuantity <= $product['stock']) {
                        $cart[$productId]['quantity'] = $newQuantity;
                    } else {
                        // If exceeds stock, set to max available
                        $cart[$productId]['quantity'] = $product['stock'];
                    }
                } else {
                    if ($quantity <= $product['stock']) {
                        $cart[$productId] = [
                            'product_id' => $productId,
                            'quantity' => $quantity,
                        ];
                    } else {
                        // If quantity exceeds stock, set to max available
                        $cart[$productId] = [
                            'product_id' => $productId,
                            'quantity' => $product['stock'],
                        ];
                    }
                }
                
                session()->set($cartKey, $cart);
                
                // Redirect to cart with success message
                return redirect()->to('/cart')->with('success', 'Pendaftaran berhasil! Produk telah ditambahkan ke keranjang');
            }
        }

        return redirect()->to('/shop')->with('success', 'Pendaftaran berhasil! Selamat datang, ' . $userData['full_name']);
    }

    public function login()
    {
        // If already logged in as customer, redirect to shop
        if (session()->get('customer_logged_in')) {
            return redirect()->to('/shop');
        }
        
        // If already logged in as admin, redirect to admin dashboard
        if (session()->get('admin_logged_in')) {
            return redirect()->to('/admin/dashboard');
        }
        
        return view('auth/login');
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
            return redirect()->back()->withInput()->with('error', 'Email atau password salah');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah');
        }

        // Get user roles
        $userRoles = $this->userModel->getUserRoles($user['id']);
        
        // Check if user has admin role (admin, manager, staff)
        $allowedAdminRoles = ['admin', 'manager', 'staff'];
        $isAdmin = false;
        $isCustomer = false;
        $userRoleNames = [];
        
        foreach ($userRoles as $role) {
            $roleName = strtolower($role['role_name']);
            $userRoleNames[] = $role['role_name'];
            
            if (in_array($roleName, $allowedAdminRoles)) {
                $isAdmin = true;
            }
            
            if ($roleName == 'customer') {
                $isCustomer = true;
            }
        }

        // If user has admin role, redirect to admin dashboard
        if ($isAdmin) {
            // Set admin session
            session()->set([
                'admin_id'        => $user['id'],
                'admin_name'      => $user['full_name'],
                'admin_email'     => $user['email'],
                'admin_roles'     => $userRoleNames,
                'admin_logged_in' => true,
            ]);

            // Log activity
            $roleName = !empty($userRoleNames) ? implode(', ', $userRoleNames) : 'Unknown';
            $this->auditLogModel->logAction($user['id'], "Login ke Admin Panel dari Shop (Role: {$roleName})");

            return redirect()->to('/admin/dashboard')->with('success', 'Selamat datang, ' . $user['full_name']);
        }

        // If user only has customer role, proceed with customer login
        if (!$isCustomer) {
            return redirect()->back()->withInput()->with('error', 'Anda tidak memiliki akses untuk login');
        }

        // Set customer session
        session()->set([
            'customer_id'        => $user['id'],
            'customer_name'      => $user['full_name'],
            'customer_email'     => $user['email'],
            'customer_logged_in' => true,
        ]);

        // Log activity
        $this->auditLogModel->logAction($user['id'], 'Login ke Customer Portal');

        // Handle pending cart item (if user tried to add to cart before login)
        // IMPORTANT: Only process if pending_cart_item exists and user just logged in
        // This prevents cross-user contamination if multiple users use the same browser/session
        $pendingCartItem = session()->get('pending_cart_item');
        if ($pendingCartItem && isset($pendingCartItem['product_id'])) {
            // Remove immediately to prevent reuse
            session()->remove('pending_cart_item');
            
            // Add product to cart using the logged-in user's ID
            $cartKey = 'cart_' . $user['id'];
            $cart = session()->get($cartKey) ?? [];
            
            $productModel = new \App\Models\ProductModel();
            $product = $productModel->find($pendingCartItem['product_id']);
            
            if ($product && $product['stock'] > 0) {
                $productId = $pendingCartItem['product_id'];
                $quantity = (int)($pendingCartItem['quantity'] ?? 1);
                
                // Validate quantity
                if ($quantity < 1) {
                    $quantity = 1;
                }
                
                if (isset($cart[$productId])) {
                    $newQuantity = $cart[$productId]['quantity'] + $quantity;
                    if ($newQuantity <= $product['stock']) {
                        $cart[$productId]['quantity'] = $newQuantity;
                    } else {
                        // If exceeds stock, set to max available
                        $cart[$productId]['quantity'] = $product['stock'];
                    }
                } else {
                    if ($quantity <= $product['stock']) {
                        $cart[$productId] = [
                            'product_id' => $productId,
                            'quantity' => $quantity,
                        ];
                    } else {
                        // If quantity exceeds stock, set to max available
                        $cart[$productId] = [
                            'product_id' => $productId,
                            'quantity' => $product['stock'],
                        ];
                    }
                }
                
                session()->set($cartKey, $cart);
                
                // Redirect to cart with success message
                return redirect()->to('/cart')->with('success', 'Produk berhasil ditambahkan ke keranjang');
            }
        }

        // Redirect to intended page or shop
        $redirectTo = session()->get('redirect_after_login') ?? '/shop';
        session()->remove('redirect_after_login');

        return redirect()->to($redirectTo)->with('success', 'Selamat datang kembali, ' . $user['full_name']);
    }

    public function logout()
    {
        // Clear customer cart before logout
        $customerId = session()->get('customer_id');
        if ($customerId) {
            session()->remove('cart_' . $customerId);
            // Log activity
            $this->auditLogModel->logAction($customerId, 'Logout dari Customer Portal');
        }
        
        // Clear pending cart item and redirect URL to prevent cross-user contamination
        session()->remove(['customer_id', 'customer_name', 'customer_email', 'customer_logged_in', 'pending_cart_item', 'redirect_after_login']);
        return redirect()->to('/shop')->with('success', 'Anda telah berhasil logout');
    }
}

