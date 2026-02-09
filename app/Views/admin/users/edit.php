<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <a href="<?= base_url('admin/users') ?>" class="inline-flex items-center text-slate-600 hover:text-slate-900 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Daftar Pengguna
    </a>
</div>

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-slate-100">
            <h2 class="text-lg font-bold text-slate-900">Edit Pengguna</h2>
            <p class="text-sm text-slate-500 mt-1">Ubah detail pengguna</p>
        </div>
        
        <form action="<?= base_url('admin/users/update/' . $user['id']) ?>" method="POST" class="p-4 sm:p-6">
            <?= csrf_field() ?>
            
            <!-- Form Fields -->
            <div class="space-y-5">
                <!-- Name & Email -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="full_name" name="full_name" value="<?= old('full_name') ?? $user['full_name'] ?>" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none text-base"
                            placeholder="Masukkan nama lengkap">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="<?= old('email') ?? $user['email'] ?>" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none text-base"
                            placeholder="email@example.com">
                    </div>
                </div>

                <!-- Password & Phone -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                            Password 
                            <span class="text-slate-400 font-normal">(Kosongkan jika tidak diubah)</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password"
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none text-base pr-12"
                                placeholder="••••••••">
                            <button type="button" onclick="togglePassword('password')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <svg id="password-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">Nomor Telepon</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </span>
                            <input type="text" id="phone" name="phone" value="<?= old('phone') ?? $user['phone'] ?>"
                                class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none text-base"
                                placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                </div>

                <!-- Roles -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Roles <span class="text-red-500">*</span></label>
                    <div class="bg-slate-50 rounded-xl p-4 space-y-3">
                        <?php foreach ($roles as $role): ?>
                        <label class="flex items-center space-x-3 cursor-pointer p-2 -mx-2 rounded-lg hover:bg-slate-100 transition-colors">
                            <input type="checkbox" name="roles[]" value="<?= $role['id'] ?>" <?= (old('roles') && in_array($role['id'], old('roles'))) || (isset($user['role_ids']) && in_array($role['id'], $user['role_ids'])) ? 'checked' : '' ?>
                                class="w-5 h-5 text-primary-600 border-slate-300 rounded focus:ring-primary-500">
                            <div>
                                <span class="text-sm text-slate-900 font-medium"><?= $role['role_name'] ?></span>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- User Info Panel -->
                <div class="bg-blue-50 rounded-xl p-4">
                    <p class="text-sm font-medium text-blue-900 mb-3">Informasi Pengguna</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
                        <div>
                            <span class="text-blue-600">ID:</span>
                            <span class="text-blue-900 font-medium ml-1">#<?= $user['id'] ?></span>
                        </div>
                        <div>
                            <span class="text-blue-600">Dibuat:</span>
                            <span class="text-blue-900 font-medium ml-1"><?= date('d/m/Y', strtotime($user['created_at'])) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 mt-8 pt-6 border-t border-slate-100">
                <a href="<?= base_url('admin/users') ?>" class="w-full sm:w-auto px-6 py-3 text-center text-slate-600 hover:text-slate-900 font-medium transition-colors border border-slate-200 rounded-xl hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    input.type = input.type === 'password' ? 'text' : 'password';
}

document.addEventListener('DOMContentLoaded', function() {
    <?php if (session()->getFlashdata('errors')): ?>
    const errors = <?= json_encode(session()->getFlashdata('errors'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const errorMessages = Object.values(errors).flat();
    
    Swal.fire({
        icon: 'error',
        title: 'Validasi Gagal',
        html: '<div class="text-left"><p class="mb-2 font-medium">Terjadi kesalahan pada form:</p><ul class="list-disc list-inside space-y-1">' + 
              errorMessages.map(msg => '<li>' + msg + '</li>').join('') + 
              '</ul></div>',
        confirmButtonColor: '#f15a47',
        confirmButtonText: 'OK',
        customClass: {
            popup: 'swal2-popup-responsive',
            title: 'swal2-title-responsive',
            htmlContainer: 'swal2-html-container-responsive',
            confirmButton: 'swal2-confirm-responsive'
        },
        buttonsStyling: false
    });
    <?php endif; ?>
});
</script>
<?= $this->endSection() ?>
