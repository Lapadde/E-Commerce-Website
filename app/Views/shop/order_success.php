<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
    <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 lg:p-12 text-center">
        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
            <svg class="w-10 h-10 sm:w-12 sm:h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-3 sm:mb-4">Pesanan Berhasil!</h1>
        <p class="text-base sm:text-lg text-slate-600 mb-6 sm:mb-8">Terima kasih atas pesanan Anda. Pesanan Anda sedang diproses.</p>
        
        <div class="bg-slate-50 rounded-xl p-4 sm:p-6 mb-6 sm:mb-8 text-left">
            <h2 class="font-bold text-slate-900 mb-3 sm:mb-4 text-base sm:text-lg">Detail Pesanan</h2>
            <div class="space-y-2 text-xs sm:text-sm text-slate-600">
                <p><span class="font-semibold">No. Pesanan:</span> #<?= $order['id'] ?></p>
                <p><span class="font-semibold">Nama:</span> <?= esc($order['customer_name']) ?></p>
                <p><span class="font-semibold">Email:</span> <?= esc($order['customer_email']) ?></p>
                <p><span class="font-semibold">Total:</span> Rp <?= number_format($order['amount'], 0, ',', '.') ?></p>
                <p class="flex flex-col sm:flex-row sm:items-center gap-2"><span class="font-semibold">Status:</span> 
                    <span class="inline-flex px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs sm:text-sm font-medium w-fit">
                        <?= ucfirst($order['order_status']) ?>
                    </span>
                </p>
            </div>
        </div>

        <div class="bg-slate-50 rounded-xl p-4 sm:p-6 mb-6 sm:mb-8 text-left">
            <h2 class="font-bold text-slate-900 mb-3 sm:mb-4 text-base sm:text-lg">Item Pesanan</h2>
            <div class="space-y-3 sm:space-y-4">
                <?php foreach ($orderItems as $item): ?>
                <div class="flex justify-between items-start sm:items-center pb-3 sm:pb-4 border-b border-slate-200 last:border-0 gap-2">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-slate-900 text-sm sm:text-base"><?= esc($item['product_name']) ?></p>
                        <p class="text-xs sm:text-sm text-slate-500"><?= $item['quantity'] ?>x Rp <?= number_format($item['price'], 0, ',', '.') ?></p>
                    </div>
                    <p class="font-semibold text-slate-900 text-sm sm:text-base flex-shrink-0">Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
            <a href="<?= base_url('/orders/' . $order['id']) ?>" class="px-5 sm:px-6 py-2.5 sm:py-3 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-lg transition-colors text-sm sm:text-base">
                Lihat Detail Pesanan
            </a>
            <a href="<?= base_url('/orders') ?>" class="px-5 sm:px-6 py-2.5 sm:py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg transition-colors text-sm sm:text-base">
                Riwayat Pesanan
            </a>
            <a href="<?= base_url('/shop') ?>" class="px-5 sm:px-6 py-2.5 sm:py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg transition-colors text-sm sm:text-base">
                Lanjut Berbelanja
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

