<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 mb-4 sm:mb-6">
    <div>
        <p class="text-xs sm:text-sm text-slate-500">Total <?= count($roles) ?> role</p>
    </div>
    <a href="<?= base_url('admin/roles/create') ?>" class="inline-flex items-center justify-center space-x-2 px-4 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25 text-sm sm:text-base">
        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <span>Tambah Role</span>
    </a>
</div>

<?php if (empty($roles)): ?>
<!-- Empty State -->
<div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-8 sm:p-12 text-center">
    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
    </div>
    <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-2">Belum ada role</h3>
    <p class="text-sm sm:text-base text-slate-500 mb-4 sm:mb-6">Mulai tambahkan role pertama</p>
    <a href="<?= base_url('admin/roles/create') ?>" class="inline-flex items-center space-x-2 text-primary-600 hover:text-primary-700 font-medium text-sm sm:text-base">
        <span>Tambah role pertama</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </a>
</div>
<?php else: ?>

<!-- Desktop Table View -->
<div class="hidden md:block bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-full">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">ID</th>
                    <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Nama Role</th>
                    <th class="px-4 lg:px-6 py-3 lg:py-4 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($roles as $role): ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-4 lg:px-6 py-3 lg:py-4">
                        <span class="text-xs sm:text-sm text-slate-600 font-medium">#<?= $role['id'] ?></span>
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4">
                        <span class="inline-flex items-center px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-full text-xs sm:text-sm font-medium <?= 
                            $role['role_name'] == 'admin' ? 'bg-violet-100 text-violet-700' : 
                            ($role['role_name'] == 'manager' ? 'bg-blue-100 text-blue-700' : 
                            ($role['role_name'] == 'staff' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-700')) 
                        ?>">
                            <?php if ($role['role_name'] == 'admin'): ?>
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <?php endif; ?>
                            <?= ucfirst($role['role_name']) ?>
                        </span>
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="<?= base_url('admin/roles/edit/' . $role['id']) ?>" class="inline-flex items-center space-x-1.5 sm:space-x-2 px-3 sm:px-4 py-1.5 sm:py-2 bg-slate-100 hover:bg-primary-50 text-slate-700 hover:text-primary-600 font-medium rounded-xl transition-colors text-xs sm:text-sm">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>Edit</span>
                            </a>
                            <form action="<?= base_url('admin/roles/delete/' . $role['id']) ?>" method="POST" class="delete-form">
                                <?= csrf_field() ?>
                                <button type="submit" class="p-1.5 sm:p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl transition-colors" title="Hapus">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Mobile Card View -->
<div class="md:hidden space-y-3">
    <?php foreach ($roles as $role): ?>
    <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-4">
        <div class="flex items-start justify-between mb-3">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs text-slate-500 font-medium">ID: #<?= $role['id'] ?></span>
                </div>
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium <?= 
                    $role['role_name'] == 'admin' ? 'bg-violet-100 text-violet-700' : 
                    ($role['role_name'] == 'manager' ? 'bg-blue-100 text-blue-700' : 
                    ($role['role_name'] == 'staff' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-700')) 
                ?>">
                    <?php if ($role['role_name'] == 'admin'): ?>
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <?php endif; ?>
                    <?= ucfirst($role['role_name']) ?>
                </span>
            </div>
        </div>
        <div class="flex items-center gap-2 pt-3 border-t border-slate-100">
            <a href="<?= base_url('admin/roles/edit/' . $role['id']) ?>" class="flex-1 flex items-center justify-center space-x-2 px-4 py-2 bg-slate-100 hover:bg-primary-50 text-slate-700 hover:text-primary-600 font-medium rounded-xl transition-colors text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span>Edit</span>
            </a>
            <form action="<?= base_url('admin/roles/delete/' . $role['id']) ?>" method="POST" class="delete-form">
                <?= csrf_field() ?>
                <button type="submit" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl transition-colors" title="Hapus">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // SweetAlert for delete confirmation
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Hapus Role?',
                text: 'Apakah Anda yakin ingin menghapus role ini? Tindakan ini tidak dapat dibatalkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
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
                    form.submit();
                }
            });
        });
    });
});
</script>
<?= $this->endSection() ?>

