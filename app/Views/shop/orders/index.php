<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-2">Riwayat Pesanan</h1>
        <p class="text-slate-600">Lihat semua pesanan yang telah Anda buat</p>
    </div>

    <?php if (empty($orders)): ?>
    <!-- Empty State -->
    <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-8 sm:p-12 text-center">
        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
        </div>
        <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-2">Belum ada pesanan</h3>
        <p class="text-sm sm:text-base text-slate-500 mb-6">Mulai berbelanja untuk melihat pesanan Anda di sini</p>
        <a href="<?= base_url('/shop') ?>" class="inline-flex items-center space-x-2 px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-xl transition-colors">
            <span>Mulai Berbelanja</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">No. Pesanan</th>
                        <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Total</th>
                        <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 lg:px-6 py-3 lg:py-4 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($orders as $order): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 lg:px-6 py-3 lg:py-4">
                            <span class="font-semibold text-primary-600">#<?= $order['id'] ?></span>
                        </td>
                        <td class="px-4 lg:px-6 py-3 lg:py-4">
                            <div>
                                <p class="text-sm lg:text-base text-slate-900"><?= date('d M Y', strtotime($order['order_date'])) ?></p>
                                <p class="text-xs text-slate-500"><?= date('H:i', strtotime($order['order_date'])) ?> WIB</p>
                            </div>
                        </td>
                        <td class="px-4 lg:px-6 py-3 lg:py-4">
                            <p class="font-bold text-slate-900 text-sm lg:text-base">Rp <?= number_format($order['amount'], 0, ',', '.') ?></p>
                        </td>
                        <td class="px-4 lg:px-6 py-3 lg:py-4">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium 
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
                        </td>
                        <td class="px-4 lg:px-6 py-3 lg:py-4 text-right">
                            <a href="<?= base_url('orders/' . $order['id']) ?>" class="inline-flex items-center space-x-2 px-4 py-2 bg-slate-100 hover:bg-primary-50 text-slate-700 hover:text-primary-600 font-medium rounded-xl transition-colors text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>Detail</span>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden space-y-4">
        <?php foreach ($orders as $order): ?>
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-4">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <span class="font-semibold text-primary-600 text-base">#<?= $order['id'] ?></span>
                    <p class="text-xs text-slate-500 mt-1"><?= date('d M Y, H:i', strtotime($order['order_date'])) ?> WIB</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium 
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
            
            <div class="border-t border-slate-100 pt-3 mb-3">
                <p class="font-bold text-slate-900 text-lg">Rp <?= number_format($order['amount'], 0, ',', '.') ?></p>
            </div>
            
            <a href="<?= base_url('orders/' . $order['id']) ?>" class="w-full inline-flex items-center justify-center space-x-2 px-4 py-2.5 bg-slate-100 hover:bg-primary-50 text-slate-700 hover:text-primary-600 font-medium rounded-xl transition-colors text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <span>Lihat Detail</span>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

