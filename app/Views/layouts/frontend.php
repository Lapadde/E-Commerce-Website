<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title><?= $title ?? 'Toko Online' ?> - E-Commerce</title>
    <link rel="icon" type="image/x-icon" href="<?= base_url('logo.png') ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('logo.png') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'jakarta': ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#fef3f2',
                            100: '#fee5e2',
                            200: '#ffcfc9',
                            300: '#fdada3',
                            400: '#fa7d6e',
                            500: '#f15a47',
                            600: '#de3c28',
                            700: '#bb2f1d',
                            800: '#9a2b1c',
                            900: '#802a1e',
                            950: '#45120a',
                        },
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* SweetAlert2 Responsive Styles */
        .swal2-popup-responsive {
            max-width: 90% !important;
            padding: 1.5rem !important;
        }
        
        @media (min-width: 640px) {
            .swal2-popup-responsive {
                max-width: 500px !important;
                padding: 2rem !important;
            }
        }
        
        .swal2-title-responsive {
            font-size: 1.25rem !important;
            margin-bottom: 0.75rem !important;
        }
        
        @media (min-width: 640px) {
            .swal2-title-responsive {
                font-size: 1.5rem !important;
                margin-bottom: 1rem !important;
            }
        }
        
        .swal2-html-container-responsive {
            font-size: 0.875rem !important;
            line-height: 1.5 !important;
            margin-top: 0.5rem !important;
        }
        
        @media (min-width: 640px) {
            .swal2-html-container-responsive {
                font-size: 1rem !important;
                margin-top: 0.75rem !important;
            }
        }
        
        .swal2-confirm-responsive {
            font-size: 0.875rem !important;
            padding: 0.5rem 1.5rem !important;
            min-width: 80px !important;
        }
        
        @media (min-width: 640px) {
            .swal2-confirm-responsive {
                font-size: 1rem !important;
                padding: 0.625rem 2rem !important;
                min-width: 100px !important;
            }
        }
        
        .swal2-cancel-responsive {
            font-size: 0.875rem !important;
            padding: 0.5rem 1.5rem !important;
            min-width: 80px !important;
        }
        
        @media (min-width: 640px) {
            .swal2-cancel-responsive {
                font-size: 1rem !important;
                padding: 0.625rem 2rem !important;
                min-width: 100px !important;
            }
        }
    </style>
</head>
<body class="h-full bg-slate-50">
    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-sm shadow-sm sticky top-0 z-50 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <!-- Logo -->
                <div class="flex items-center flex-shrink-0">
                    <a href="<?= base_url('/shop') ?>" class="flex items-center space-x-2 sm:space-x-3 group">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/30 group-hover:shadow-primary-500/50 transition-all duration-300">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <span class="text-lg sm:text-xl font-bold text-slate-900 hidden sm:block">E-Commerce</span>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden lg:flex items-center space-x-1">
                    <a href="<?= base_url('/shop') ?>" class="px-4 py-2 text-sm font-semibold text-slate-700 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Beranda</span>
                    </a>
                    <a href="<?= base_url('/shop') ?>" class="px-4 py-2 text-sm font-semibold text-slate-700 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span>Produk</span>
                    </a>
                    <a href="<?= base_url('/cart') ?>" class="relative px-4 py-2 text-sm font-semibold text-slate-700 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>Keranjang</span>
                        <?php 
                        if (session()->get('customer_logged_in')):
                            $customerId = session()->get('customer_id');
                            $cartKey = 'cart_' . $customerId;
                            $cart = session()->get($cartKey) ?? [];
                            $cartCount = array_sum(array_column($cart, 'quantity'));
                            if ($cartCount > 0): 
                        ?>
                        <span class="absolute -top-1 -right-1 bg-primary-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center shadow-lg"><?= $cartCount ?></span>
                        <?php 
                            endif;
                        endif; 
                        ?>
                    </a>
                </div>
                    
                <!-- Desktop User Menu -->
                <div class="hidden lg:flex items-center space-x-3">
                    <?php if (session()->get('customer_logged_in') || session()->get('admin_logged_in')): ?>
                        <?php 
                        $isAdmin = session()->get('admin_logged_in');
                        $userName = $isAdmin ? session()->get('admin_name') : session()->get('customer_name');
                        ?>
                        <!-- User Dropdown -->
                        <div class="relative group" id="userDropdown">
                            <button class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-slate-100 transition-colors focus:outline-none">
                                <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                    <?= strtoupper(substr($userName, 0, 1)) ?>
                                </div>
                                <span class="text-sm font-medium text-slate-700 hidden xl:block"><?= esc($userName) ?></span>
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="userDropdownMenu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-slate-100 py-2 z-50">
                                <div class="px-4 py-3 border-b border-slate-100">
                                    <p class="text-sm font-semibold text-slate-900"><?= esc($userName) ?></p>
                                    <p class="text-xs text-slate-500"><?= $isAdmin ? 'Admin' : 'Customer' ?></p>
                                </div>
                                <?php if (!$isAdmin): ?>
                                <a href="<?= base_url('/orders') ?>" class="flex items-center space-x-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <span>Pesanan Saya</span>
                                </a>
                                <?php endif; ?>
                                <a href="<?= $isAdmin ? base_url('/admin/dashboard') : base_url('/profile') ?>" class="flex items-center space-x-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span><?= $isAdmin ? 'Dashboard' : 'Profil' ?></span>
                                </a>
                                <?php if ($isAdmin): ?>
                                <a href="<?= base_url('/admin/dashboard') ?>" class="flex items-center space-x-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    <span>Admin Panel</span>
                                </a>
                                <?php endif; ?>
                                <div class="border-t border-slate-100 my-2"></div>
                                <a href="<?= $isAdmin ? base_url('/admin/logout') : base_url('/logout') ?>" 
                                   class="logout-link flex items-center space-x-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                                   data-logout-url="<?= $isAdmin ? base_url('/admin/logout') : base_url('/logout') ?>">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span>Logout</span>
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                    <div class="flex items-center space-x-2">
                        <a href="<?= base_url('/login') ?>" class="px-4 py-2 text-sm font-semibold text-slate-700 hover:text-primary-600 transition-colors">
                            Login
                        </a>
                        <a href="<?= base_url('/register') ?>" class="px-4 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white text-sm font-semibold rounded-lg transition-all shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40">
                            Daftar
                        </a>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <div class="lg:hidden">
                    <button id="mobileMenuBtn" class="p-2 text-slate-700 hover:bg-slate-100 rounded-lg transition-colors focus:outline-none">
                        <svg id="menuIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg id="closeIcon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden lg:hidden bg-white border-t border-slate-100 shadow-lg">
            <div class="px-4 py-4 space-y-1">
                <a href="<?= base_url('/shop') ?>" class="flex items-center space-x-3 px-3 py-2.5 text-slate-700 hover:bg-slate-50 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="font-medium">Beranda</span>
                </a>
                <a href="<?= base_url('/shop') ?>" class="flex items-center space-x-3 px-3 py-2.5 text-slate-700 hover:bg-slate-50 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span class="font-medium">Produk</span>
                </a>
                <a href="<?= base_url('/cart') ?>" class="relative flex items-center space-x-3 px-3 py-2.5 text-slate-700 hover:bg-slate-50 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="font-medium">Keranjang</span>
                    <?php 
                    if (session()->get('customer_logged_in')):
                        $customerId = session()->get('customer_id');
                        $cartKey = 'cart_' . $customerId;
                        $cart = session()->get($cartKey) ?? [];
                        $cartCount = array_sum(array_column($cart, 'quantity'));
                        if ($cartCount > 0): 
                    ?>
                    <span class="absolute right-3 bg-primary-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center"><?= $cartCount ?></span>
                    <?php 
                        endif;
                    endif; 
                    ?>
                </a>
                
                <?php if (session()->get('customer_logged_in') || session()->get('admin_logged_in')): ?>
                    <?php 
                    $isAdmin = session()->get('admin_logged_in');
                    $userName = $isAdmin ? session()->get('admin_name') : session()->get('customer_name');
                    ?>
                <div class="pt-3 mt-3 border-t border-slate-200">
                    <div class="flex items-center space-x-3 px-3 py-2 mb-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                            <?= strtoupper(substr($userName, 0, 1)) ?>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900"><?= esc($userName) ?></p>
                            <p class="text-xs text-slate-500"><?= $isAdmin ? 'Admin' : 'Customer' ?></p>
                        </div>
                    </div>
                    <?php if (!$isAdmin): ?>
                    <a href="<?= base_url('/orders') ?>" class="flex items-center space-x-3 px-3 py-2.5 text-slate-700 hover:bg-slate-50 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span class="font-medium">Pesanan Saya</span>
                    </a>
                    <?php endif; ?>
                    <a href="<?= $isAdmin ? base_url('/admin/dashboard') : base_url('/profile') ?>" class="flex items-center space-x-3 px-3 py-2.5 text-slate-700 hover:bg-slate-50 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="font-medium"><?= $isAdmin ? 'Dashboard' : 'Profil' ?></span>
                    </a>
                    <?php if ($isAdmin): ?>
                    <a href="<?= base_url('/admin/dashboard') ?>" class="flex items-center space-x-3 px-3 py-2.5 text-slate-700 hover:bg-slate-50 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span class="font-medium">Admin Panel</span>
                    </a>
                    <?php endif; ?>
                    <a href="<?= $isAdmin ? base_url('/admin/logout') : base_url('/logout') ?>" 
                       class="logout-link flex items-center space-x-3 px-3 py-2.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors mt-2"
                       data-logout-url="<?= $isAdmin ? base_url('/admin/logout') : base_url('/logout') ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="font-medium">Logout</span>
                    </a>
                </div>
                <?php else: ?>
                <div class="pt-3 mt-3 border-t border-slate-200 space-y-2">
                    <a href="<?= base_url('/login') ?>" class="block px-3 py-2.5 text-slate-700 hover:bg-slate-50 rounded-lg transition-colors font-medium text-center">
                        Login
                    </a>
                    <a href="<?= base_url('/register') ?>" class="block px-3 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-lg transition-all shadow-lg shadow-primary-500/25 text-center font-semibold">
                        Daftar
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Flash Messages will be shown via SweetAlert -->

    <!-- Main Content -->
    <main class="min-h-screen">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">E-Commerce</h3>
                    <p class="text-slate-400">Toko online terpercaya untuk semua kebutuhan Anda.</p>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Tautan Cepat</h3>
                    <ul class="space-y-2 text-slate-400">
                        <li><a href="<?= base_url('/shop') ?>" class="hover:text-white">Beranda</a></li>
                        <li><a href="<?= base_url('/shop') ?>" class="hover:text-white">Produk</a></li>
                        <li><a href="<?= base_url('/cart') ?>" class="hover:text-white">Keranjang</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Kontak</h3>
                    <p class="text-slate-400">Email: tpktpk25@gmail.com</p>
                    <p class="text-slate-400">Telp: 081242818675</p>
                </div>
            </div>
            <div class="border-t border-slate-800 mt-8 pt-8 text-center text-slate-400">
                <p>&copy; <?= date('Y') ?> E-Commerce. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuIcon = document.getElementById('menuIcon');
        const closeIcon = document.getElementById('closeIcon');
        
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                const isHidden = mobileMenu.classList.contains('hidden');
                if (isHidden) {
                    mobileMenu.classList.remove('hidden');
                    menuIcon.classList.add('hidden');
                    closeIcon.classList.remove('hidden');
                } else {
                    mobileMenu.classList.add('hidden');
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                }
            });
        }

        // User dropdown toggle
        const userDropdown = document.getElementById('userDropdown');
        const userDropdownMenu = document.getElementById('userDropdownMenu');
        
        if (userDropdown && userDropdownMenu) {
            userDropdown.addEventListener('click', (e) => {
                e.stopPropagation();
                userDropdownMenu.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!userDropdown.contains(e.target)) {
                    userDropdownMenu.classList.add('hidden');
                }
            });
        }

        // Logout confirmation with SweetAlert
        const logoutLinks = document.querySelectorAll('.logout-link');
        logoutLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const logoutUrl = this.getAttribute('data-logout-url');
                
                Swal.fire({
                    title: 'Konfirmasi Logout',
                    text: 'Apakah Anda yakin ingin keluar?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Logout',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    confirmButtonColor: '#f15a47',
                    cancelButtonColor: '#6b7280',
                    customClass: {
                        popup: 'swal2-popup-responsive',
                        title: 'swal2-title-responsive',
                        htmlContainer: 'swal2-html-container-responsive',
                        confirmButton: 'swal2-confirm-responsive',
                        cancelButton: 'swal2-cancel-responsive'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = logoutUrl;
                    }
                });
            });
        });

        // SweetAlert for Flash Messages
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: <?= json_encode(session()->getFlashdata('success'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
                confirmButtonColor: '#f15a47',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true,
                position: 'center',
                width: window.innerWidth <= 640 ? '90%' : '500px',
                customClass: {
                    popup: 'swal2-popup-responsive',
                    title: 'swal2-title-responsive',
                    htmlContainer: 'swal2-html-container-responsive',
                    confirmButton: 'swal2-confirm-responsive'
                }
            });
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: <?= json_encode(session()->getFlashdata('error'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
                confirmButtonColor: '#f15a47',
                confirmButtonText: 'OK',
                position: 'center',
                width: window.innerWidth <= 640 ? '90%' : '500px',
                customClass: {
                    popup: 'swal2-popup-responsive',
                    title: 'swal2-title-responsive',
                    htmlContainer: 'swal2-html-container-responsive',
                    confirmButton: 'swal2-confirm-responsive'
                }
            });
            <?php endif; ?>

            <?php if (session()->getFlashdata('info')): ?>
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: <?= json_encode(session()->getFlashdata('info'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
                confirmButtonColor: '#f15a47',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true,
                position: 'center',
                width: window.innerWidth <= 640 ? '90%' : '500px',
                customClass: {
                    popup: 'swal2-popup-responsive',
                    title: 'swal2-title-responsive',
                    htmlContainer: 'swal2-html-container-responsive',
                    confirmButton: 'swal2-confirm-responsive'
                }
            });
            <?php endif; ?>

            <?php if (session()->getFlashdata('warning')): ?>
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: <?= json_encode(session()->getFlashdata('warning'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
                confirmButtonColor: '#f15a47',
                confirmButtonText: 'OK',
                position: 'center',
                width: window.innerWidth <= 640 ? '90%' : '500px',
                customClass: {
                    popup: 'swal2-popup-responsive',
                    title: 'swal2-title-responsive',
                    htmlContainer: 'swal2-html-container-responsive',
                    confirmButton: 'swal2-confirm-responsive'
                }
            });
            <?php endif; ?>
        });
    </script>
</body>
</html>

