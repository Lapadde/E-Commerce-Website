<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Filter Section -->
<div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-6 mb-6">
    <form method="GET" action="<?= base_url('admin/reports') ?>" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Mulai</label>
            <input type="date" 
                   name="start_date" 
                   value="<?= $startDate ?>" 
                   class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
        </div>
        <div class="flex-1">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Akhir</label>
            <input type="date" 
                   name="end_date" 
                   value="<?= $endDate ?>" 
                   class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25">
                Filter
            </button>
            <div class="flex gap-2">
                <a href="<?= base_url('admin/reports/export?format=xlsx&start_date=' . $startDate . '&end_date=' . $endDate) ?>" 
                   class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-emerald-500/25 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Excel
                </a>
                <a href="<?= base_url('admin/reports/export?format=pdf&start_date=' . $startDate . '&end_date=' . $endDate) ?>" 
                   target="_blank"
                   class="px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-red-500/25 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    PDF
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl p-6 text-white shadow-xl shadow-primary-500/25">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <p class="text-sm font-medium text-white/80 mb-1">Total Pendapatan</p>
        <p class="text-2xl font-bold">Rp <?= number_format($salesData['totalRevenue'] ?? 0, 0, ',', '.') ?></p>
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-6 text-white shadow-xl shadow-blue-500/25">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
        </div>
        <p class="text-sm font-medium text-white/80 mb-1">Total Pesanan</p>
        <p class="text-2xl font-bold"><?= number_format($salesData['totalOrders'] ?? 0, 0, ',', '.') ?></p>
    </div>

    <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-6 text-white shadow-xl shadow-emerald-500/25">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <p class="text-sm font-medium text-white/80 mb-1">Pesanan Selesai</p>
        <p class="text-2xl font-bold"><?= number_format($salesData['completedOrders'] ?? 0, 0, ',', '.') ?></p>
    </div>

    <div class="bg-gradient-to-br from-amber-500 to-amber-700 rounded-2xl p-6 text-white shadow-xl shadow-amber-500/25">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
        </div>
        <p class="text-sm font-medium text-white/80 mb-1">Rata-rata Pesanan</p>
        <p class="text-2xl font-bold">Rp <?= number_format($salesData['averageOrder'] ?? 0, 0, ',', '.') ?></p>
    </div>
</div>

<!-- Top Products -->
<div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-6 mb-6">
    <h3 class="text-lg font-bold text-slate-900 mb-4">Produk Terlaris</h3>
    <?php if (empty($topProducts)): ?>
        <p class="text-slate-500 text-center py-8">Tidak ada data produk</p>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Produk</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Terjual</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($topProducts as $product): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3">
                            <span class="font-medium text-slate-900"><?= $product['name'] ?></span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="text-slate-600"><?= number_format($product['total_quantity'] ?? 0, 0, ',', '.') ?> unit</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="font-semibold text-slate-900">Rp <?= number_format($product['total_revenue'] ?? 0, 0, ',', '.') ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Sales by Status -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-6">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Pesanan Berdasarkan Status</h3>
        <?php if (empty($salesByStatus)): ?>
            <p class="text-slate-500 text-center py-8">Tidak ada data</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($salesByStatus as $status): ?>
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                    <span class="font-medium text-slate-700"><?= ucfirst($status['order_status']) ?></span>
                    <div class="text-right">
                        <p class="font-semibold text-slate-900"><?= number_format($status['count'] ?? 0, 0, ',', '.') ?> pesanan</p>
                        <p class="text-sm text-slate-500">Rp <?= number_format($status['total'] ?? 0, 0, ',', '.') ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-6">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Penjualan Harian</h3>
        <?php if (empty($dailySales)): ?>
            <p class="text-slate-500 text-center py-8">Tidak ada data</p>
        <?php else: ?>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                <?php foreach ($dailySales as $daily): ?>
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                    <span class="font-medium text-slate-700"><?= date('d M Y', strtotime($daily['date'])) ?></span>
                    <div class="text-right">
                        <p class="font-semibold text-slate-900"><?= number_format($daily['orders'] ?? 0, 0, ',', '.') ?> pesanan</p>
                        <p class="text-sm text-slate-500">Rp <?= number_format($daily['revenue'] ?? 0, 0, ',', '.') ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

