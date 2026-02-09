<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto px-4 sm:px-0">
    <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-4 sm:p-6 lg:p-8">
        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mb-4 sm:mb-6">Edit Pelanggan</h2>

        <form action="<?= base_url('admin/customers/update/' . $customer['id']) ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="space-y-6">
                <div>
                    <label for="full_name" class="block text-sm font-semibold text-slate-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="full_name" 
                           name="full_name" 
                           value="<?= old('full_name', $customer['full_name']) ?>"
                           class="w-full px-4 py-2.5 sm:py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors text-sm sm:text-base"
                           placeholder="Masukkan nama lengkap"
                           required>
                    <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['full_name'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= session()->getFlashdata('errors')['full_name'] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="<?= old('email', $customer['email']) ?>"
                           class="w-full px-4 py-2.5 sm:py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors text-sm sm:text-base"
                           placeholder="contoh@email.com"
                           required>
                    <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['email'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= session()->getFlashdata('errors')['email'] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                        Password Baru <span class="text-slate-400 text-xs">(kosongkan jika tidak ingin mengubah)</span>
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="w-full px-4 py-2.5 sm:py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors text-sm sm:text-base"
                           placeholder="Minimal 6 karakter">
                    <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['password'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= session()->getFlashdata('errors')['password'] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-700 mb-2">
                        Telepon
                    </label>
                    <input type="text" 
                           id="phone" 
                           name="phone" 
                           value="<?= old('phone', $customer['phone']) ?>"
                           class="w-full px-4 py-2.5 sm:py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors text-sm sm:text-base"
                           placeholder="081234567890">
                    <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['phone'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= session()->getFlashdata('errors')['phone'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4 pt-4">
                    <button type="submit" class="flex-1 px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25 text-sm sm:text-base">
                        Update Pelanggan
                    </button>
                    <a href="<?= base_url('admin/customers') ?>" class="w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-colors text-center text-sm sm:text-base">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
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

