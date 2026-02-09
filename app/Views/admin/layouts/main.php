<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel' ?> - E-Commerce</title>
    <link rel="icon" type="image/x-icon" href="<?= base_url('logo.png') ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('logo.png') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
                        dark: {
                            50: '#f6f6f6',
                            100: '#e7e7e7',
                            200: '#d1d1d1',
                            300: '#b0b0b0',
                            400: '#888888',
                            500: '#6d6d6d',
                            600: '#5d5d5d',
                            700: '#4f4f4f',
                            800: '#454545',
                            900: '#1a1a1a',
                            950: '#0d0d0d',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .sidebar-link.active { background: linear-gradient(135deg, #f15a47 0%, #de3c28 100%); color: white; }
        .sidebar-link:hover:not(.active) { background-color: rgba(241, 90, 71, 0.1); }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); }
        @keyframes slideIn { from { opacity: 0; transform: translateX(-10px); } to { opacity: 1; transform: translateX(0); } }
        .animate-slide-in { animation: slideIn 0.3s ease-out forwards; }
    </style>
</head>
<body class="h-full bg-gradient-to-br from-slate-50 via-white to-slate-100">
    <div class="min-h-full flex">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-dark-900 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-between h-20 px-6 border-b border-dark-800">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white">E-Commerce</span>
                    </div>
                    <button id="closeSidebar" class="lg:hidden text-dark-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                    <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl text-dark-300 transition-all duration-200 <?= (uri_string() == 'admin/dashboard' || uri_string() == 'admin') ? 'active' : '' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <?php if (hasRole(['admin', 'manager', 'staff'])): ?>
                    <a href="<?= base_url('admin/products') ?>" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl text-dark-300 transition-all duration-200 <?= strpos(uri_string(), 'admin/products') === 0 ? 'active' : '' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span class="font-medium">Produk</span>
                    </a>

                    <a href="<?= base_url('admin/categories') ?>" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl text-dark-300 transition-all duration-200 <?= strpos(uri_string(), 'admin/categories') === 0 ? 'active' : '' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <span class="font-medium">Kategori</span>
                    </a>

                    <a href="<?= base_url('admin/orders') ?>" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl text-dark-300 transition-all duration-200 <?= strpos(uri_string(), 'admin/orders') === 0 ? 'active' : '' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <span class="font-medium">Pesanan</span>
                    </a>

                    <a href="<?= base_url('admin/customers') ?>" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl text-dark-300 transition-all duration-200 <?= strpos(uri_string(), 'admin/customers') === 0 ? 'active' : '' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="font-medium">Pelanggan</span>
                    </a>
                    <?php endif; ?>

                    <?php if (hasRole('admin')): ?>
                    <a href="<?= base_url('admin/users') ?>" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl text-dark-300 transition-all duration-200 <?= strpos(uri_string(), 'admin/users') === 0 ? 'active' : '' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span class="font-medium">Pengguna</span>
                    </a>

                    <a href="<?= base_url('admin/roles') ?>" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl text-dark-300 transition-all duration-200 <?= strpos(uri_string(), 'admin/roles') === 0 ? 'active' : '' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span class="font-medium">Role</span>
                    </a>
                    <?php endif; ?>

                    <?php if (hasRole(['admin', 'manager'])): ?>
                    <a href="<?= base_url('admin/reports') ?>" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl text-dark-300 transition-all duration-200 <?= strpos(uri_string(), 'admin/reports') === 0 ? 'active' : '' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="font-medium">Laporan Penjualan</span>
                    </a>
                    <?php endif; ?>

                    <?php if (hasRole('admin')): ?>
                    <a href="<?= base_url('admin/audit-logs') ?>" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl text-dark-300 transition-all duration-200 <?= strpos(uri_string(), 'admin/audit-logs') === 0 ? 'active' : '' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="font-medium">Activity Log</span>
                    </a>
                    <?php endif; ?>
                </nav>

                <!-- User Profile -->
                <div class="p-4 border-t border-dark-800">
                    <div class="flex items-center space-x-3 px-4 py-3 rounded-xl bg-dark-800/50">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-semibold">
                            <?= substr(session()->get('admin_name') ?? 'A', 0, 1) ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate"><?= session()->get('admin_name') ?? 'Admin' ?></p>
                            <p class="text-xs text-dark-400 truncate"><?= session()->get('admin_email') ?? '' ?></p>
                            <?php 
                            $roles = session()->get('admin_roles', []);
                            if (!empty($roles)): 
                            ?>
                            <p class="text-xs text-primary-400 truncate mt-1">
                                <?= implode(', ', array_map('ucfirst', $roles)) ?>
                            </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Overlay for mobile -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden"></div>

        <!-- Main Content -->
        <div class="flex-1 lg:ml-72">
            <!-- Top Navigation -->
            <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-slate-200">
                <div class="flex items-center justify-between h-16 px-4 lg:px-8">
                    <div class="flex items-center space-x-4">
                        <button id="openSidebar" class="lg:hidden p-2 rounded-lg text-dark-600 hover:bg-slate-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <h1 class="text-xl font-bold text-dark-900"><?= $title ?? 'Dashboard' ?></h1>
                    </div>

                    <div class="flex items-center space-x-4">
                        <a href="<?= base_url() ?>" target="_blank" class="hidden sm:flex items-center space-x-2 px-4 py-2 text-sm text-dark-600 hover:text-dark-900 hover:bg-slate-100 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            <span>Lihat Toko</span>
                        </a>
                        <button type="button" id="logoutBtn" class="flex items-center space-x-2 px-4 py-2 text-sm text-white bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 rounded-lg transition-all shadow-lg shadow-primary-500/25">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 lg:p-8">
                <!-- Flash Messages - Hidden (using SweetAlert instead) -->
                <?php if (session()->getFlashdata('success')): ?>
                <div class="hidden"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                <div class="hidden"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')): ?>
                <div class="hidden"><?= json_encode(session()->getFlashdata('errors')) ?></div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
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
        .swal2-confirm-responsive, .swal2-cancel-responsive {
            font-size: 0.875rem !important;
            padding: 0.5rem 1.5rem !important;
            min-width: 80px !important;
        }
        @media (min-width: 640px) {
            .swal2-confirm-responsive, .swal2-cancel-responsive {
                font-size: 1rem !important;
                padding: 0.75rem 2rem !important;
            }
        }
    </style>
    <script>
        // Mobile sidebar toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const openSidebar = document.getElementById('openSidebar');
        const closeSidebar = document.getElementById('closeSidebar');

        function toggleSidebar(show) {
            if (show) {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            }
        }

        openSidebar?.addEventListener('click', () => toggleSidebar(true));
        closeSidebar?.addEventListener('click', () => toggleSidebar(false));
        sidebarOverlay?.addEventListener('click', () => toggleSidebar(false));

        // SweetAlert for Flash Messages
        document.addEventListener('DOMContentLoaded', function() {
            const swalConfig = {
                confirmButtonColor: '#f15a47',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'swal2-popup-responsive',
                    title: 'swal2-title-responsive',
                    htmlContainer: 'swal2-html-container-responsive',
                    confirmButton: 'swal2-confirm-responsive',
                    cancelButton: 'swal2-cancel-responsive'
                },
                buttonsStyling: false,
            };

            <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: <?= json_encode(session()->getFlashdata('success'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
                timer: 3000,
                timerProgressBar: true,
                position: 'center',
                ...swalConfig
            });
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: <?= json_encode(session()->getFlashdata('error'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
                position: 'center',
                ...swalConfig
            });
            <?php endif; ?>

            <?php if (session()->getFlashdata('info')): ?>
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: <?= json_encode(session()->getFlashdata('info'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
                timer: 3000,
                timerProgressBar: true,
                position: 'center',
                ...swalConfig
            });
            <?php endif; ?>

            <?php if (session()->getFlashdata('warning')): ?>
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: <?= json_encode(session()->getFlashdata('warning'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
                position: 'center',
                ...swalConfig
            });
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')): ?>
            const errors = <?= json_encode(session()->getFlashdata('errors'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
            const errorMessages = Object.values(errors).flat();
            
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: '<div class="text-left"><p class="mb-2 font-medium">Terjadi kesalahan pada form:</p><ul class="list-disc list-inside space-y-1">' + 
                      errorMessages.map(msg => '<li>' + msg + '</li>').join('') + 
                      '</ul></div>',
                position: 'center',
                ...swalConfig
            });
            <?php endif; ?>

            // SweetAlert for logout confirmation
            const logoutBtn = document.getElementById('logoutBtn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    Swal.fire({
                        title: 'Logout?',
                        text: 'Apakah Anda yakin ingin keluar dari sistem?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#f15a47',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Logout',
                        cancelButtonText: 'Batal',
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
                            window.location.href = '<?= base_url('admin/logout') ?>';
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>

