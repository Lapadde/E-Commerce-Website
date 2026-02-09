<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Revenue -->
    <div class="bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl p-6 text-white shadow-xl shadow-primary-500/25">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <span class="text-xs font-medium bg-white/20 px-2 py-1 rounded-full">Total</span>
        </div>
        <p class="text-sm font-medium text-white/80 mb-1">Total Pendapatan</p>
        <p class="text-2xl font-bold">Rp <?= number_format($totalRevenue ?? 0, 0, ',', '.') ?></p>
    </div>

    <!-- Monthly Revenue -->
    <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
            <span class="text-xs font-medium text-emerald-600 bg-emerald-100 px-2 py-1 rounded-full"><?= date('M Y') ?></span>
        </div>
        <p class="text-sm font-medium text-slate-500 mb-1">Pendapatan Bulan Ini</p>
        <p class="text-2xl font-bold text-slate-900">Rp <?= number_format($monthlyRevenue ?? 0, 0, ',', '.') ?></p>
    </div>

    <!-- Total Orders -->
    <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <?php if (($pendingOrders ?? 0) > 0): ?>
            <span class="text-xs font-medium text-amber-600 bg-amber-100 px-2 py-1 rounded-full"><?= $pendingOrders ?> pending</span>
            <?php endif; ?>
        </div>
        <p class="text-sm font-medium text-slate-500 mb-1">Total Pesanan</p>
        <p class="text-2xl font-bold text-slate-900"><?= number_format($totalOrders ?? 0) ?></p>
    </div>

    <!-- Total Products -->
    <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
        </div>
        <p class="text-sm font-medium text-slate-500 mb-1">Total Produk</p>
        <p class="text-2xl font-bold text-slate-900"><?= number_format($totalProducts ?? 0) ?></p>
    </div>
</div>

<!-- Quick Stats Row -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-slate-50 rounded-xl p-4 flex items-center space-x-3">
        <div class="w-10 h-10 bg-slate-200 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
        </div>
        <div>
            <p class="text-xs text-slate-500">Kategori</p>
            <p class="text-lg font-bold text-slate-900"><?= $totalCategories ?? 0 ?></p>
        </div>
    </div>
    <div class="bg-slate-50 rounded-xl p-4 flex items-center space-x-3">
        <div class="w-10 h-10 bg-slate-200 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
        </div>
        <div>
            <p class="text-xs text-slate-500">Pengguna</p>
            <p class="text-lg font-bold text-slate-900"><?= $totalUsers ?? 0 ?></p>
        </div>
    </div>
    <div class="bg-amber-50 rounded-xl p-4 flex items-center space-x-3">
        <div class="w-10 h-10 bg-amber-200 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <div>
            <p class="text-xs text-slate-500">Stok Menipis</p>
            <p class="text-lg font-bold text-amber-600"><?= count($lowStockProducts ?? []) ?></p>
        </div>
    </div>
    <div class="bg-blue-50 rounded-xl p-4 flex items-center space-x-3">
        <div class="w-10 h-10 bg-blue-200 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <p class="text-xs text-slate-500">Pesanan Pending</p>
            <p class="text-lg font-bold text-blue-600"><?= $pendingOrders ?? 0 ?></p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Orders -->
    <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-slate-100">
            <h2 class="text-lg font-bold text-slate-900">Pesanan Terbaru</h2>
            <a href="<?= base_url('admin/orders') ?>" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Lihat Semua</a>
        </div>
        <div class="divide-y divide-slate-100">
            <?php if (empty($recentOrders)): ?>
            <div class="p-6 text-center text-slate-500">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <p>Belum ada pesanan</p>
            </div>
            <?php else: ?>
            <?php foreach (array_slice($recentOrders, 0, 5) as $order): ?>
            <a href="<?= base_url('admin/orders/' . $order['id']) ?>" class="flex items-center justify-between p-4 hover:bg-slate-50 transition-colors">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-slate-900">#<?= $order['id'] ?></p>
                        <p class="text-sm text-slate-500"><?= $order['customer_name'] ?></p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-slate-900">Rp <?= number_format($order['amount'], 0, ',', '.') ?></p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                        <?php
                        switch($order['order_status']) {
                            case 'pending': echo 'bg-amber-100 text-amber-700'; break;
                            case 'processing': echo 'bg-blue-100 text-blue-700'; break;
                            case 'shipped': echo 'bg-violet-100 text-violet-700'; break;
                            case 'delivered': echo 'bg-emerald-100 text-emerald-700'; break;
                            case 'cancelled': echo 'bg-red-100 text-red-700'; break;
                            default: echo 'bg-slate-100 text-slate-700';
                        }
                        ?>">
                        <?= ucfirst($order['order_status']) ?>
                    </span>
                </div>
            </a>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Low Stock Products -->
    <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-slate-100">
            <h2 class="text-lg font-bold text-slate-900">Produk Stok Menipis</h2>
            <a href="<?= base_url('admin/products') ?>" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Lihat Semua</a>
        </div>
        <div class="divide-y divide-slate-100">
            <?php if (empty($lowStockProducts)): ?>
            <div class="p-6 text-center text-slate-500">
                <svg class="w-12 h-12 text-emerald-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-emerald-600">Semua stok produk aman!</p>
            </div>
            <?php else: ?>
            <?php foreach (array_slice($lowStockProducts, 0, 5) as $product): ?>
            <a href="<?= base_url('admin/products/edit/' . $product['id']) ?>" class="flex items-center justify-between p-4 hover:bg-slate-50 transition-colors">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-slate-100 rounded-lg overflow-hidden">
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
                    <div>
                        <p class="font-medium text-slate-900"><?= $product['name'] ?></p>
                        <p class="text-sm text-slate-500">Rp <?= number_format($product['price'], 0, ',', '.') ?></p>
                    </div>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-700">
                    <?= $product['stock'] ?> tersisa
                </span>
            </a>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

