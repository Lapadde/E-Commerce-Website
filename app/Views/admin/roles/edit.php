<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto px-4 sm:px-0">
    <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-4 sm:p-6 lg:p-8">
        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mb-4 sm:mb-6">Edit Role</h2>

        <form action="<?= base_url('admin/roles/update/' . $role['id']) ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="space-y-4 sm:space-y-6">
                <div>
                    <label for="role_name" class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">
                        Nama Role <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="role_name" 
                           name="role_name" 
                           value="<?= old('role_name', $role['role_name']) ?>"
                           class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors text-sm sm:text-base"
                           placeholder="Contoh: admin, manager, staff"
                           required>
                    <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['role_name'])): ?>
                        <p class="mt-1 text-xs sm:text-sm text-red-600"><?= session()->getFlashdata('errors')['role_name'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4 pt-2 sm:pt-4">
                    <button type="submit" class="flex-1 sm:flex-none px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25 text-sm sm:text-base">
                        Update Role
                    </button>
                    <a href="<?= base_url('admin/roles') ?>" class="w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-colors text-center text-sm sm:text-base">
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

