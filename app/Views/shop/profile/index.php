<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-2">Profil Saya</h1>
        <p class="text-slate-600">Kelola informasi profil Anda</p>
    </div>

    <!-- Profile Information Card -->
    <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-4 sm:p-6 lg:p-8 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Informasi Profil</h2>
            <a href="<?= base_url('/profile/change-password') ?>" class="inline-flex items-center space-x-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl transition-colors text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
                <span>Ubah Password</span>
            </a>
        </div>

        <form action="<?= base_url('/profile/update') ?>" method="POST" class="space-y-6">
            <?= csrf_field() ?>

            <!-- Full Name -->
            <div>
                <label for="full_name" class="block text-sm font-medium text-slate-700 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="full_name" 
                       name="full_name" 
                       value="<?= old('full_name', $customer['full_name']) ?>" 
                       required
                       class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors text-sm sm:text-base">
                <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['full_name'])): ?>
                <p class="mt-1 text-sm text-red-600"><?= session()->getFlashdata('errors')['full_name'] ?></p>
                <?php endif; ?>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="<?= old('email', $customer['email']) ?>" 
                       required
                       class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors text-sm sm:text-base">
                <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['email'])): ?>
                <p class="mt-1 text-sm text-red-600"><?= session()->getFlashdata('errors')['email'] ?></p>
                <?php endif; ?>
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">
                    Nomor Telepon
                </label>
                <input type="tel" 
                       id="phone" 
                       name="phone" 
                       value="<?= old('phone', $customer['phone'] ?? '') ?>" 
                       class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors text-sm sm:text-base">
                <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['phone'])): ?>
                <p class="mt-1 text-sm text-red-600"><?= session()->getFlashdata('errors')['phone'] ?></p>
                <?php endif; ?>
            </div>

            <!-- Account Info (Read-only) -->
            <div class="pt-6 border-t border-slate-200">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">Informasi Akun</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-slate-500 mb-1">Tanggal Daftar</p>
                        <p class="font-medium text-slate-900">
                            <?= $customer['created_at'] ? date('d M Y, H:i', strtotime($customer['created_at'])) : '-' ?> WIB
                        </p>
                    </div>
                    <div>
                        <p class="text-slate-500 mb-1">Terakhir Diupdate</p>
                        <p class="font-medium text-slate-900">
                            <?= $customer['updated_at'] ? date('d M Y, H:i', strtotime($customer['updated_at'])) : '-' ?> WIB
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4">
                <button type="submit" class="flex-1 sm:flex-none px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-xl transition-colors">
                    Simpan Perubahan
                </button>
                <a href="<?= base_url('/shop') ?>" class="flex-1 sm:flex-none px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-colors text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

