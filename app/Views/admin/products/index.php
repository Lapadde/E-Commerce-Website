<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Search and Header -->
<div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-6 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex-1">
            <form method="GET" action="<?= base_url('admin/products') ?>" class="flex gap-3">
                <div class="flex-1 relative">
                    <input type="text" 
                           name="search" 
                           value="<?= esc($search ?? '') ?>" 
                           placeholder="Cari produk (nama, SKU, deskripsi)..." 
                           class="w-full px-4 py-2.5 pl-11 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <button type="submit" class="px-6 py-2.5 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-xl transition-colors">
                    Cari
                </button>
                <?php if ($search): ?>
                <a href="<?= base_url('admin/products') ?>" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-colors">
                    Reset
                </a>
                <?php endif; ?>
            </form>
        </div>
        <a href="<?= base_url('admin/products/create') ?>" class="inline-flex items-center justify-center space-x-2 px-5 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span>Tambah Produk</span>
        </a>
    </div>
    <div class="mt-4 text-sm text-slate-500">
        Total <?= number_format($total ?? 0, 0, ',', '.') ?> produk ditemukan
    </div>
</div>

<?php if (empty($products)): ?>
<!-- Empty State -->
<div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-12 text-center">
    <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
    </svg>
    <h3 class="text-lg font-semibold text-slate-900 mb-2">Belum ada produk</h3>
    <p class="text-slate-500 mb-6">Mulai tambahkan produk pertama Anda</p>
    <a href="<?= base_url('admin/products/create') ?>" class="inline-flex items-center space-x-2 text-primary-600 hover:text-primary-700 font-medium">
        <span>Tambah produk pertama</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </a>
</div>
<?php else: ?>

<!-- Mobile Card View -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:hidden">
    <?php foreach ($products as $product): ?>
    <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <!-- Product Image -->
        <div class="aspect-square bg-slate-100 relative">
            <?php if ($product['image']): ?>
            <img src="<?= base_url('uploads/products/' . $product['image']) ?>" alt="<?= $product['name'] ?>" class="w-full h-full object-cover">
            <?php else: ?>
            <div class="w-full h-full flex items-center justify-center">
                <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <?php endif; ?>
            
            <!-- Status Badges -->
            <div class="absolute top-3 left-3 flex flex-wrap gap-2">
                <span class="px-2 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">SKU: <?= $product['sku'] ?></span>
            </div>
            
            <!-- Stock Badge -->
            <div class="absolute top-3 right-3">
                <span class="px-2 py-1 rounded-full text-xs font-medium <?= $product['stock'] <= 10 ? 'bg-red-100 text-red-700' : 'bg-white/90 text-slate-700' ?>">
                    Stok: <?= $product['stock'] ?>
                </span>
            </div>
        </div>
        
        <!-- Product Info -->
        <div class="p-4">
            <p class="text-xs text-slate-500 mb-1"><?= $product['category_names'] ?? 'Tanpa Kategori' ?></p>
            <h3 class="font-semibold text-slate-900 mb-2 line-clamp-2"><?= $product['name'] ?></h3>
            
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="font-bold text-lg text-primary-600">Rp <?= number_format($product['price'], 0, ',', '.') ?></p>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center gap-2">
                <a href="<?= base_url('admin/products/edit/' . $product['id']) ?>" class="flex-1 flex items-center justify-center space-x-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit</span>
                </a>
                <form action="<?= base_url('admin/products/delete/' . $product['id']) ?>" method="POST" class="delete-form">
                    <?= csrf_field() ?>
                    <button type="submit" class="p-2.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </form>
            </div>
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
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Produk</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Harga</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Stok</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($products as $product): ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 bg-slate-100 rounded-xl overflow-hidden flex-shrink-0">
                                <?php if ($product['image']): ?>
                                <img src="<?= base_url('uploads/products/' . $product['image']) ?>" alt="<?= $product['name'] ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium text-slate-900 truncate max-w-xs"><?= $product['name'] ?></p>
                                <p class="text-xs text-slate-500 mt-1">SKU: <?= $product['sku'] ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm bg-slate-100 text-slate-700">
                            <?= $product['category_names'] ?? '-' ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-slate-900">Rp <?= number_format($product['price'], 0, ',', '.') ?></p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium <?= $product['stock'] <= 10 ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-700' ?>">
                            <?= $product['stock'] ?> unit
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-slate-500 text-sm">-</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="<?= base_url('admin/products/edit/' . $product['id']) ?>" class="inline-flex items-center space-x-2 px-4 py-2 bg-slate-100 hover:bg-primary-50 text-slate-700 hover:text-primary-600 font-medium rounded-xl transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>Edit</span>
                            </a>
                            <form action="<?= base_url('admin/products/delete/' . $product['id']) ?>" method="POST" class="delete-form">
                                <?= csrf_field() ?>
                                <button type="submit" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl transition-colors" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

<!-- Pagination -->
<?php if (isset($pager) && $pager->hasMore('products')): ?>
<div class="mt-6 flex justify-center">
    <div class="flex items-center space-x-2">
        <?php 
        $currentPage = $pager->getCurrentPage('products');
        $totalPages = $pager->getPageCount('products');
        $perPage = $pager->getPerPage('products');
        
        // Previous button
        if ($currentPage > 1): 
            $prevPage = $currentPage - 1;
            $prevUrl = base_url('admin/products') . '?page=' . $prevPage . ($search ? '&search=' . urlencode($search) : '');
        ?>
        <a href="<?= $prevUrl ?>" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <?php endif; ?>
        
        <!-- Page numbers -->
        <?php
        $startPage = max(1, $currentPage - 2);
        $endPage = min($totalPages, $currentPage + 2);
        
        for ($i = $startPage; $i <= $endPage; $i++):
            $pageUrl = base_url('admin/products') . '?page=' . $i . ($search ? '&search=' . urlencode($search) : '');
        ?>
        <a href="<?= $pageUrl ?>" class="px-4 py-2 <?= $i == $currentPage ? 'bg-primary-500 text-white' : 'bg-white border border-slate-300 text-slate-700 hover:bg-slate-50' ?> rounded-lg transition-colors">
            <?= $i ?>
        </a>
        <?php endfor; ?>
        
        <!-- Next button -->
        <?php if ($currentPage < $totalPages): 
            $nextPage = $currentPage + 1;
            $nextUrl = base_url('admin/products') . '?page=' . $nextPage . ($search ? '&search=' . urlencode($search) : '');
        ?>
        <a href="<?= $nextUrl ?>" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                title: 'Hapus Produk?',
                text: 'Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.',
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
