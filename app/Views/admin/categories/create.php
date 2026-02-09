<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <a href="<?= base_url('admin/categories') ?>" class="inline-flex items-center text-slate-600 hover:text-slate-900 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Daftar Kategori
    </a>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-lg font-bold text-slate-900">Informasi Kategori</h2>
            <p class="text-sm text-slate-500 mt-1">Isi detail kategori yang akan ditambahkan</p>
        </div>
        
        <form action="<?= base_url('admin/categories/store') ?>" method="POST" class="p-6 space-y-6">
            <?= csrf_field() ?>
            
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="<?= old('name') ?>" required
                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none"
                    placeholder="Masukkan nama kategori">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Deskripsi</label>
                <textarea id="description" name="description" rows="4"
                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none resize-none"
                    placeholder="Masukkan deskripsi kategori"><?= old('description') ?></textarea>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-100">
                <a href="<?= base_url('admin/categories') ?>" class="px-6 py-2.5 text-slate-600 hover:text-slate-900 font-medium transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25">
                    Simpan Kategori
                </button>
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

