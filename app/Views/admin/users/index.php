<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <p class="text-slate-500">Total <?= count($users) ?> pengguna</p>
    </div>
    <a href="<?= base_url('admin/users/create') ?>" class="inline-flex items-center justify-center space-x-2 px-5 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <span>Tambah Pengguna</span>
    </a>
</div>

<?php if (empty($users)): ?>
<!-- Empty State -->
<div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-12 text-center">
    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-slate-900 mb-2">Belum ada pengguna</h3>
    <p class="text-slate-500 mb-6">Mulai tambahkan pengguna pertama</p>
    <a href="<?= base_url('admin/users/create') ?>" class="inline-flex items-center space-x-2 text-primary-600 hover:text-primary-700 font-medium">
        <span>Tambah pengguna pertama</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </a>
</div>
<?php else: ?>

<!-- Mobile Card View -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:hidden">
    <?php foreach ($users as $user): ?>
    <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <!-- Card Header with Avatar -->
        <div class="p-4 bg-gradient-to-br from-slate-50 to-white border-b border-slate-100">
            <div class="flex items-center space-x-4">
                <!-- Avatar -->
                <div class="w-16 h-16 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white text-xl font-bold flex-shrink-0 shadow-lg shadow-primary-500/25">
                    <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                </div>
                <!-- User Info -->
                <div class="min-w-0 flex-1">
                    <h3 class="font-semibold text-slate-900 truncate"><?= $user['full_name'] ?></h3>
                    <p class="text-sm text-slate-500 truncate"><?= $user['email'] ?></p>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <!-- Role Badges -->
                        <?php if (!empty($user['roles'])): ?>
                            <?php foreach ($user['roles'] as $role): ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium <?= $role['role_name'] == 'admin' ? 'bg-violet-100 text-violet-700' : 'bg-blue-100 text-blue-700' ?>">
                                <?php if ($role['role_name'] == 'admin'): ?>
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <?php endif; ?>
                                <?= ucfirst($role['role_name']) ?>
                            </span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500">
                                No Role
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Card Body -->
        <div class="p-4 space-y-3">
            <!-- Phone -->
            <?php if ($user['phone']): ?>
            <div class="flex items-center space-x-3 text-sm">
                <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <span class="text-slate-600"><?= $user['phone'] ?></span>
            </div>
            <?php endif; ?>
            
            <!-- Registered Date -->
            <div class="flex items-center space-x-3 text-sm">
                <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <span class="text-slate-600">Bergabung <?= date('d M Y', strtotime($user['created_at'])) ?></span>
            </div>
        </div>
        
        <!-- Card Actions -->
        <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center gap-2">
            <a href="<?= base_url('admin/users/edit/' . $user['id']) ?>" class="flex-1 flex items-center justify-center space-x-2 px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-medium rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span>Edit</span>
            </a>
            <?php if ($user['id'] != session()->get('admin_id')): ?>
            <form action="<?= base_url('admin/users/delete/' . $user['id']) ?>" method="POST" class="delete-form">
                <?= csrf_field() ?>
                <button type="submit" class="p-2.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </form>
            <?php else: ?>
            <span class="p-2.5 bg-slate-100 text-slate-400 rounded-xl cursor-not-allowed" title="Tidak dapat menghapus diri sendiri">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </span>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Desktop Table View -->
<div class="hidden lg:block bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Pengguna</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Telepon</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Terdaftar</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($users as $user): ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0 shadow-md shadow-primary-500/20">
                                <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium text-slate-900"><?= $user['full_name'] ?></p>
                                <p class="text-sm text-slate-500 truncate"><?= $user['email'] ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1">
                            <?php if (!empty($user['roles'])): ?>
                                <?php foreach ($user['roles'] as $role): ?>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?= $role['role_name'] == 'admin' ? 'bg-violet-100 text-violet-700' : 'bg-blue-100 text-blue-700' ?>">
                                    <?= ucfirst($role['role_name']) ?>
                                </span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-500">
                                    No Role
                                </span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <?php if ($user['phone']): ?>
                        <span class="text-slate-600"><?= $user['phone'] ?></span>
                        <?php else: ?>
                        <span class="text-slate-400">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-slate-500 text-sm">-</span>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="text-slate-900"><?= date('d M Y', strtotime($user['created_at'])) ?></p>
                            <p class="text-xs text-slate-500"><?= date('H:i', strtotime($user['created_at'])) ?> WIB</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="<?= base_url('admin/users/edit/' . $user['id']) ?>" class="inline-flex items-center space-x-2 px-4 py-2 bg-slate-100 hover:bg-primary-50 text-slate-700 hover:text-primary-600 font-medium rounded-xl transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>Edit</span>
                            </a>
                            <?php if ($user['id'] != session()->get('admin_id')): ?>
                            <form action="<?= base_url('admin/users/delete/' . $user['id']) ?>" method="POST" class="delete-form">
                                <?= csrf_field() ?>
                                <button type="submit" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl transition-colors" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                            <?php else: ?>
                            <span class="p-2 bg-slate-100 text-slate-400 rounded-xl cursor-not-allowed" title="Tidak dapat menghapus diri sendiri">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
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
                title: 'Hapus Pengguna?',
                text: 'Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.',
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
