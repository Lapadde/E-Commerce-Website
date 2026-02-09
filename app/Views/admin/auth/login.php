<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - E-Commerce</title>
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
                        },
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-delay { animation: float 6s ease-in-out 2s infinite; }
    </style>
</head>
<body class="h-full">
    <div class="min-h-full flex">
        <!-- Left Side - Decorative -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 relative overflow-hidden">
            <!-- Abstract shapes -->
            <div class="absolute inset-0">
                <div class="absolute top-20 left-20 w-72 h-72 bg-primary-500/20 rounded-full blur-3xl animate-float"></div>
                <div class="absolute bottom-20 right-20 w-96 h-96 bg-primary-600/10 rounded-full blur-3xl animate-float-delay"></div>
                <div class="absolute top-1/2 left-1/3 w-64 h-64 bg-primary-400/10 rounded-full blur-3xl"></div>
            </div>
            
            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-center px-16 text-white">
                <div class="mb-8">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl flex items-center justify-center mb-6 shadow-2xl shadow-primary-500/30">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold mb-4">E-Commerce<br/>Admin Panel</h1>
                    <p class="text-slate-400 text-lg max-w-md">Kelola toko online Anda dengan mudah dan efisien. Dashboard yang powerful untuk mengontrol semua aspek bisnis Anda.</p>
                </div>
                
                <div class="grid grid-cols-2 gap-6 mt-8">
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                        <div class="w-12 h-12 bg-primary-500/20 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold mb-1">Kelola Produk</h3>
                        <p class="text-sm text-slate-400">Tambah, edit, hapus produk dengan mudah</p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                        <div class="w-12 h-12 bg-primary-500/20 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold mb-1">Kelola Pesanan</h3>
                        <p class="text-sm text-slate-400">Pantau dan proses pesanan pelanggan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gradient-to-br from-slate-50 to-white">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl shadow-primary-500/30">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-slate-900">E-Commerce Admin</h1>
                </div>

                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-slate-900">Selamat Datang!</h2>
                        <p class="text-slate-500 mt-2">Masuk ke dashboard admin Anda</p>
                    </div>

                    <!-- Flash Messages - Hidden (using SweetAlert instead) -->
                    <?php if (session()->getFlashdata('error')): ?>
                    <div class="hidden"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>
                    <div class="hidden"><?= session()->getFlashdata('success') ?></div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/login') ?>" method="POST" class="space-y-6">
                        <?= csrf_field() ?>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <input type="email" id="email" name="email" value="<?= old('email') ?>" required
                                    class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none"
                                    placeholder="admin@example.com">
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input type="password" id="password" name="password" required
                                    class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none"
                                    placeholder="••••••••">
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/30 hover:shadow-primary-500/40 transform hover:-translate-y-0.5">
                            Masuk
                        </button>
                    </form>

                    <!-- Button to Shop -->
                    <div class="mt-6 pt-6 border-t border-slate-200">
                        <a href="<?= base_url('shop') ?>" class="w-full flex items-center justify-center space-x-2 py-3 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span>Kembali ke Beranda</span>
                        </a>
                    </div>
                </div>

                <p class="text-center text-sm text-slate-500 mt-8">
                    &copy; <?= date('Y') ?> E-Commerce. All rights reserved.
                </p>
            </div>
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
        });
    </script>
</body>
</html>

