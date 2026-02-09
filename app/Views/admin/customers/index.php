<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Search and Header -->
<div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-4 sm:p-6 mb-4 sm:mb-6">
    <div class="flex flex-col gap-4">
        <div class="flex-1">
            <form method="GET" action="<?= base_url('admin/customers') ?>" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <input type="text" 
                           name="search" 
                           value="<?= esc($search ?? '') ?>" 
                           placeholder="Cari pelanggan (nama, email, telepon)..." 
                           class="w-full px-4 py-2.5 pl-10 sm:pl-11 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 sm:flex-none px-4 sm:px-6 py-2.5 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-xl transition-colors text-sm sm:text-base">
                        Cari
                    </button>
                    <?php if ($search): ?>
                    <a href="<?= base_url('admin/customers') ?>" class="flex-1 sm:flex-none px-4 sm:px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-colors text-sm sm:text-base">
                        Reset
                    </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="text-xs sm:text-sm text-slate-500">
                Total <?= number_format($total ?? 0, 0, ',', '.') ?> pelanggan ditemukan
            </div>
            <a href="<?= base_url('admin/customers/create') ?>" class="inline-flex items-center justify-center space-x-2 px-4 sm:px-5 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25 text-sm sm:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Tambah Pelanggan</span>
            </a>
        </div>
    </div>
</div>

<?php if (empty($customers)): ?>
<!-- Empty State -->
<div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-8 sm:p-12 text-center">
    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
    </div>
    <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-2">Belum ada pelanggan</h3>
    <p class="text-sm sm:text-base text-slate-500 mb-6">Mulai tambahkan pelanggan pertama</p>
    <a href="<?= base_url('admin/customers/create') ?>" class="inline-flex items-center space-x-2 text-primary-600 hover:text-primary-700 font-medium text-sm sm:text-base">
        <span>Tambah pelanggan pertama</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </a>
</div>
<?php else: ?>

<!-- Desktop Table View -->
<div class="hidden md:block bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Email</th>
                    <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Telepon</th>
                    <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Terdaftar</th>
                    <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Diperbarui</th>
                    <th class="px-4 lg:px-6 py-3 lg:py-4 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($customers as $customer): ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-4 lg:px-6 py-3 lg:py-4">
                        <div class="flex items-center space-x-3 lg:space-x-4">
                            <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0 shadow-md shadow-primary-500/20 text-sm lg:text-base">
                                <?= strtoupper(substr($customer['full_name'], 0, 1)) ?>
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium text-slate-900 text-sm lg:text-base"><?= esc($customer['full_name']) ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4">
                        <span class="text-slate-600 text-sm lg:text-base"><?= esc($customer['email']) ?></span>
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4">
                        <?php if ($customer['phone']): ?>
                        <span class="text-slate-600 text-sm lg:text-base"><?= esc($customer['phone']) ?></span>
                        <?php else: ?>
                        <span class="text-slate-400 text-sm">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4">
                        <div>
                            <p class="text-slate-900 text-sm lg:text-base"><?= date('d M Y', strtotime($customer['created_at'])) ?></p>
                            <p class="text-xs text-slate-500"><?= date('H:i', strtotime($customer['created_at'])) ?> WIB</p>
                        </div>
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4">
                        <?php if (isset($customer['updated_at']) && $customer['updated_at']): ?>
                        <div>
                            <p class="text-slate-900 text-sm lg:text-base"><?= date('d M Y', strtotime($customer['updated_at'])) ?></p>
                            <p class="text-xs text-slate-500"><?= date('H:i', strtotime($customer['updated_at'])) ?> WIB</p>
                        </div>
                        <?php else: ?>
                        <span class="text-slate-400 text-sm">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="<?= base_url('admin/customers/edit/' . $customer['id']) ?>" class="inline-flex items-center space-x-1 lg:space-x-2 px-3 lg:px-4 py-1.5 lg:py-2 bg-slate-100 hover:bg-primary-50 text-slate-700 hover:text-primary-600 font-medium rounded-xl transition-colors text-xs lg:text-sm">
                                <svg class="w-3.5 h-3.5 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>Edit</span>
                            </a>
                            <form action="<?= base_url('admin/customers/delete/' . $customer['id']) ?>" method="POST" class="delete-form">
                                <?= csrf_field() ?>
                                <button type="submit" class="p-1.5 lg:p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl transition-colors" title="Hapus">
                                    <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
<div class="md:hidden space-y-4">
    <?php foreach ($customers as $customer): ?>
    <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-4">
        <div class="flex items-start space-x-3 mb-3">
            <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0 shadow-md shadow-primary-500/20">
                <?= strtoupper(substr($customer['full_name'], 0, 1)) ?>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-semibold text-slate-900 text-base mb-1"><?= esc($customer['full_name']) ?></h3>
                <p class="text-sm text-slate-600 mb-1 break-all"><?= esc($customer['email']) ?></p>
                <?php if ($customer['phone']): ?>
                <p class="text-sm text-slate-600"><?= esc($customer['phone']) ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-3 mb-4 text-xs text-slate-500 border-t border-slate-100 pt-3">
            <div>
                <p class="font-medium text-slate-700 mb-1">Terdaftar</p>
                <p><?= date('d M Y', strtotime($customer['created_at'])) ?></p>
                <p><?= date('H:i', strtotime($customer['created_at'])) ?> WIB</p>
            </div>
            <div>
                <p class="font-medium text-slate-700 mb-1">Diperbarui</p>
                <?php if (isset($customer['updated_at']) && $customer['updated_at']): ?>
                <p><?= date('d M Y', strtotime($customer['updated_at'])) ?></p>
                <p><?= date('H:i', strtotime($customer['updated_at'])) ?> WIB</p>
                <?php else: ?>
                <p class="text-slate-400">-</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="flex gap-2">
            <a href="<?= base_url('admin/customers/edit/' . $customer['id']) ?>" class="flex-1 inline-flex items-center justify-center space-x-2 px-4 py-2 bg-slate-100 hover:bg-primary-50 text-slate-700 hover:text-primary-600 font-medium rounded-xl transition-colors text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span>Edit</span>
            </a>
            <form action="<?= base_url('admin/customers/delete/' . $customer['id']) ?>" method="POST" class="delete-form flex-shrink-0">
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

<!-- Pagination -->
<?php if (isset($pager) && $pager->hasMore('customers')): ?>
<div class="mt-4 sm:mt-6 flex justify-center">
    <div class="flex items-center space-x-1 sm:space-x-2">
        <?php 
        $currentPage = $pager->getCurrentPage('customers');
        $totalPages = $pager->getPageCount('customers');
        
        // Previous button
        if ($currentPage > 1): 
            $prevPage = $currentPage - 1;
            $prevUrl = base_url('admin/customers') . '?page=' . $prevPage . ($search ? '&search=' . urlencode($search) : '');
        ?>
        <a href="<?= $prevUrl ?>" class="px-3 sm:px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <?php endif; ?>
        
        <!-- Page numbers -->
        <?php
        $startPage = max(1, $currentPage - 2);
        $endPage = min($totalPages, $currentPage + 2);
        
        for ($i = $startPage; $i <= $endPage; $i++):
            $pageUrl = base_url('admin/customers') . '?page=' . $i . ($search ? '&search=' . urlencode($search) : '');
        ?>
        <a href="<?= $pageUrl ?>" class="px-3 sm:px-4 py-2 text-xs sm:text-sm <?= $i == $currentPage ? 'bg-primary-500 text-white' : 'bg-white border border-slate-300 text-slate-700 hover:bg-slate-50' ?> rounded-lg transition-colors">
            <?= $i ?>
        </a>
        <?php endfor; ?>
        
        <!-- Next button -->
        <?php if ($currentPage < $totalPages): 
            $nextPage = $currentPage + 1;
            $nextUrl = base_url('admin/customers') . '?page=' . $nextPage . ($search ? '&search=' . urlencode($search) : '');
        ?>
        <a href="<?= $nextUrl ?>" class="px-3 sm:px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // SweetAlert for delete confirmation
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Hapus Pelanggan?',
                text: 'Apakah Anda yakin ingin menghapus pelanggan ini? Tindakan ini tidak dapat dibatalkan.',
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

