<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'ShopController::index');
$routes->get('/mugiwara', 'Home::dbTest');

// Customer Auth Routes
$routes->group('', function ($routes) {
    $routes->get('register', 'CustomerAuthController::register');
    $routes->post('register', 'CustomerAuthController::attemptRegister');
    $routes->get('login', 'CustomerAuthController::login');
    $routes->post('login', 'CustomerAuthController::attemptLogin');
    $routes->get('logout', 'CustomerAuthController::logout');
});

// Shop Routes (Customer Frontend)
$routes->group('shop', function ($routes) {
    $routes->get('search', 'ShopController::search');
    $routes->get('(:num)', 'ShopController::show/$1');
    $routes->get('/', 'ShopController::index');
});

// Cart Routes (Protected - requires login)
$routes->group('cart', ['filter' => 'customerAuth'], function ($routes) {
    $routes->get('/', 'CartController::index');
    $routes->post('add', 'CartController::add');
    $routes->post('update', 'CartController::update');
    $routes->get('remove/(:num)', 'CartController::remove/$1');
    $routes->get('clear', 'CartController::clear');
});

// Checkout Routes (Protected - requires login)
$routes->group('checkout', ['filter' => 'customerAuth'], function ($routes) {
    $routes->get('/', 'CheckoutController::index');
    $routes->post('process', 'CheckoutController::process');
    $routes->get('success/(:num)', 'CheckoutController::success/$1');
});

// Payment Routes
$routes->group('payment', function ($routes) {
    $routes->get('process/(:num)', 'PaymentController::process/$1', ['filter' => 'customerAuth']);
    $routes->post('notification', 'PaymentController::notification');
    $routes->get('finish', 'PaymentController::finish');
    $routes->get('unfinish', 'PaymentController::unfinish');
    $routes->get('error', 'PaymentController::error');
});

// Order History Routes (Protected - requires login)
$routes->group('orders', ['filter' => 'customerAuth'], function ($routes) {
    $routes->get('/', 'OrderController::index');
    $routes->get('(:num)', 'OrderController::show/$1');
});

// Profile Routes (Protected - requires login)
$routes->group('profile', ['filter' => 'customerAuth'], function ($routes) {
    $routes->get('/', 'ProfileController::index');
    $routes->post('update', 'ProfileController::update');
    $routes->get('change-password', 'ProfileController::changePassword');
    $routes->post('update-password', 'ProfileController::updatePassword');
});

// Admin Routes
$routes->group('admin', function ($routes) {
    // Auth Routes (tanpa filter)
    $routes->get('login', 'Admin\AuthController::login');
    $routes->post('login', 'Admin\AuthController::attemptLogin');
    $routes->get('logout', 'Admin\AuthController::logout');
    
    // Forbidden page (accessible by logged in users)
    $routes->get('forbidden', 'Admin\ForbiddenController::index', ['filter' => 'adminAuth']);

    // Protected Routes (dengan filter adminAuth)
    $routes->group('', ['filter' => 'adminAuth'], function ($routes) {
        // Dashboard - accessible by all admin roles
        $routes->get('/', 'Admin\DashboardController::index');
        $routes->get('dashboard', 'Admin\DashboardController::index');

        // Products - accessible by admin, manager, staff
        $routes->group('products', ['filter' => 'roleBased:admin,manager,staff'], function ($routes) {
            $routes->get('/', 'Admin\ProductController::index');
            $routes->get('create', 'Admin\ProductController::create');
            $routes->post('store', 'Admin\ProductController::store');
            $routes->get('edit/(:num)', 'Admin\ProductController::edit/$1');
            $routes->post('update/(:num)', 'Admin\ProductController::update/$1');
            $routes->post('delete/(:num)', 'Admin\ProductController::delete/$1');
        });

        // Categories - accessible by admin, manager, staff
        $routes->group('categories', ['filter' => 'roleBased:admin,manager,staff'], function ($routes) {
            $routes->get('/', 'Admin\CategoryController::index');
            $routes->get('create', 'Admin\CategoryController::create');
            $routes->post('store', 'Admin\CategoryController::store');
            $routes->get('edit/(:num)', 'Admin\CategoryController::edit/$1');
            $routes->post('update/(:num)', 'Admin\CategoryController::update/$1');
            $routes->post('delete/(:num)', 'Admin\CategoryController::delete/$1');
        });

        // Orders - accessible by admin, manager, staff
        $routes->group('orders', ['filter' => 'roleBased:admin,manager,staff'], function ($routes) {
            $routes->get('/', 'Admin\OrderController::index');
            $routes->get('(:num)', 'Admin\OrderController::show/$1');
            $routes->post('update-status/(:num)', 'Admin\OrderController::updateStatus/$1');
            $routes->post('update-payment/(:num)', 'Admin\OrderController::updatePaymentStatus/$1');
        });

        // Customers - accessible by admin, manager, staff
        $routes->group('customers', ['filter' => 'roleBased:admin,manager,staff'], function ($routes) {
            $routes->get('/', 'Admin\CustomerController::index');
            $routes->get('create', 'Admin\CustomerController::create');
            $routes->post('store', 'Admin\CustomerController::store');
            $routes->get('edit/(:num)', 'Admin\CustomerController::edit/$1');
            $routes->post('update/(:num)', 'Admin\CustomerController::update/$1');
            $routes->post('delete/(:num)', 'Admin\CustomerController::delete/$1');
        });

        // Users - accessible by admin only (sesuai use case: Kelola User hanya Admin)
        $routes->group('users', ['filter' => 'roleBased:admin'], function ($routes) {
            $routes->get('/', 'Admin\UserController::index');
            $routes->get('create', 'Admin\UserController::create');
            $routes->post('store', 'Admin\UserController::store');
            $routes->get('edit/(:num)', 'Admin\UserController::edit/$1');
            $routes->post('update/(:num)', 'Admin\UserController::update/$1');
            $routes->post('delete/(:num)', 'Admin\UserController::delete/$1');
        });

        // Roles - accessible by admin only (sesuai use case: Kelola Role hanya Admin)
        $routes->group('roles', ['filter' => 'roleBased:admin'], function ($routes) {
            $routes->get('/', 'Admin\RoleController::index');
            $routes->get('create', 'Admin\RoleController::create');
            $routes->post('store', 'Admin\RoleController::store');
            $routes->get('edit/(:num)', 'Admin\RoleController::edit/$1');
            $routes->post('update/(:num)', 'Admin\RoleController::update/$1');
            $routes->post('delete/(:num)', 'Admin\RoleController::delete/$1');
        });

        // Reports - accessible by admin and manager only (sesuai use case: Lihat Laporan Penjualan)
        $routes->group('reports', ['filter' => 'roleBased:admin,manager'], function ($routes) {
            $routes->get('/', 'Admin\ReportController::index');
            $routes->get('export', 'Admin\ReportController::export');
        });

        // Audit Logs - accessible by admin only
        $routes->group('audit-logs', ['filter' => 'roleBased:admin'], function ($routes) {
            $routes->get('/', 'Admin\AuditLogController::index');
        });
    });
});
