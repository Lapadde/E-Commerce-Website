<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Header with Stats -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <p class="text-slate-500">Total <?= count($orders) ?> pesanan</p>
    </div>
    <!-- Filter Buttons (Optional) -->
    <div class="flex flex-wrap gap-2">
        <span class="px-3 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-sm font-medium">Semua</span>
    </div>
</div>

<?php if (empty($orders)): ?>
<!-- Empty State -->
<div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-12 text-center">
    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-slate-900 mb-2">Belum ada pesanan</h3>
    <p class="text-slate-500">Pesanan dari pelanggan akan muncul di sini</p>
</div>
<?php else: ?>

<!-- Mobile Card View -->
<div class="space-y-4 lg:hidden">
    <?php foreach ($orders as $order): ?>
    <a href="<?= base_url('admin/orders/' . $order['id']) ?>" class="block bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden hover:shadow-xl transition-shadow">
        <!-- Card Header -->
        <div class="p-4 border-b border-slate-100">
            <div class="flex items-center justify-between mb-2">
                <span class="font-semibold text-primary-600">#<?= $order['id'] ?></span>
                <span class="text-xs text-slate-500"><?= date('d M Y', strtotime($order['order_date'] ?? $order['created_at'] ?? 'now')) ?></span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                    <?= substr($order['customer_name'], 0, 1) ?>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-medium text-slate-900 truncate"><?= $order['customer_name'] ?></p>
                    <p class="text-xs text-slate-500 truncate"><?= $order['customer_email'] ?></p>
                </div>
            </div>
        </div>
        
        <!-- Card Body -->
        <div class="p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-2xl font-bold text-slate-900">Rp <?= number_format($order['amount'], 0, ',', '.') ?></span>
            </div>
            
            <!-- Status Badge -->
            <div class="flex flex-wrap gap-2">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-slate-500"></span>
                    <?= ucfirst($order['order_status']) ?>
                </span>
            </div>
        </div>
        
        <!-- Card Footer -->
        <div class="px-4 py-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
            <span class="text-xs text-slate-500"><?= date('H:i', strtotime($order['order_date'] ?? $order['created_at'] ?? 'now')) ?> WIB</span>
            <span class="inline-flex items-center text-primary-600 text-sm font-medium">
                Lihat Detail
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </span>
        </div>
    </a>
    <?php endforeach; ?>
</div>

<!-- Desktop Table View -->
<div class="hidden lg:block bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">No. Pesanan</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($orders as $order): ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <a href="<?= base_url('admin/orders/' . $order['id']) ?>" class="font-semibold text-primary-600 hover:text-primary-700">
                            #<?= $order['id'] ?>
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-semibold">
                                <?= substr($order['customer_name'], 0, 1) ?>
                            </div>
                            <div>
                                <p class="font-medium text-slate-900"><?= $order['customer_name'] ?></p>
                                <p class="text-sm text-slate-500"><?= $order['customer_email'] ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-900">Rp <?= number_format($order['amount'], 0, ',', '.') ?></p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-slate-500"></span>
                            <?= ucfirst($order['order_status']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-slate-500 text-sm">-</span>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="text-slate-900"><?= date('d M Y', strtotime($order['order_date'] ?? $order['created_at'] ?? 'now')) ?></p>
                            <p class="text-sm text-slate-500"><?= date('H:i', strtotime($order['order_date'] ?? $order['created_at'] ?? 'now')) ?> WIB</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="<?= base_url('admin/orders/' . $order['id']) ?>" class="inline-flex items-center space-x-2 px-4 py-2 bg-slate-100 hover:bg-primary-50 text-slate-700 hover:text-primary-600 font-medium rounded-xl transition-colors">
                            <span>Detail</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
<?= $this->endSection() ?>
