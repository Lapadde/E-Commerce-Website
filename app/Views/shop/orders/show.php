<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="<?= base_url('/orders') ?>" class="inline-flex items-center space-x-2 text-primary-600 hover:text-primary-700 font-medium mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span>Kembali ke Riwayat Pesanan</span>
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Detail Pesanan #<?= $order['id'] ?></h1>
    </div>

    <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-4 sm:p-6 lg:p-8">
        <!-- Order Status -->
        <div class="mb-6 pb-6 border-b border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500 mb-1">Status Pesanan</p>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium 
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
                <div class="text-right">
                    <p class="text-sm text-slate-500 mb-1">Tanggal Pesanan</p>
                    <p class="font-medium text-slate-900"><?= date('d M Y, H:i', strtotime($order['order_date'])) ?> WIB</p>
                </div>
            </div>
        </div>

        <!-- Order Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="font-semibold text-slate-900 mb-3">Informasi Pelanggan</h3>
                <div class="space-y-2 text-sm text-slate-600">
                    <p><span class="font-medium">Nama:</span> <?= esc($order['customer_name']) ?></p>
                    <p><span class="font-medium">Email:</span> <?= esc($order['customer_email']) ?></p>
                    <?php if (isset($order['customer_phone']) && $order['customer_phone']): ?>
                    <p><span class="font-medium">Telepon:</span> <?= esc($order['customer_phone']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <h3 class="font-semibold text-slate-900 mb-3">Alamat Pengiriman</h3>
                <p class="text-sm text-slate-600"><?= esc($order['shipping_address']) ?></p>
            </div>
        </div>

        <!-- Order Items -->
        <div class="mb-6">
            <h3 class="font-semibold text-slate-900 mb-4">Item Pesanan</h3>
            
            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Produk</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Harga</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Qty</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($orderItems as $item): ?>
                        <tr>
                            <td class="px-4 py-4">
                                <div class="flex items-center space-x-3">
                                    <?php if (isset($item['product_image']) && $item['product_image']): ?>
                                    <img src="<?= base_url('uploads/products/' . $item['product_image']) ?>" 
                                         alt="<?= esc($item['product_name']) ?>" 
                                         class="w-12 h-12 object-cover rounded-lg">
                                    <?php else: ?>
                                    <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <?php endif; ?>
                                    <div>
                                        <p class="font-medium text-slate-900"><?= esc($item['product_name']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <p class="text-slate-600">Rp <?= number_format($item['price'], 0, ',', '.') ?></p>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <p class="text-slate-600"><?= $item['quantity'] ?></p>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <p class="font-semibold text-slate-900">Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></p>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden space-y-4">
                <?php foreach ($orderItems as $item): ?>
                <div class="bg-slate-50 rounded-xl p-4">
                    <div class="flex items-start space-x-3 mb-3">
                        <?php if (isset($item['product_image']) && $item['product_image']): ?>
                        <img src="<?= base_url('uploads/products/' . $item['product_image']) ?>" 
                             alt="<?= esc($item['product_name']) ?>" 
                             class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                        <?php else: ?>
                        <div class="w-16 h-16 bg-slate-200 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <?php endif; ?>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-slate-900 mb-1"><?= esc($item['product_name']) ?></p>
                            <p class="text-sm text-slate-500">Rp <?= number_format($item['price'], 0, ',', '.') ?> x <?= $item['quantity'] ?></p>
                        </div>
                        <p class="font-semibold text-slate-900">Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="border-t border-slate-100 pt-6">
            <div class="flex justify-between items-center">
                <span class="text-lg font-semibold text-slate-900">Total Pembayaran</span>
                <span class="text-2xl font-bold text-primary-600">Rp <?= number_format($order['amount'], 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- Payment Status -->
        <?php if (isset($order['payment_status'])): ?>
        <div class="mb-6 pb-6 border-b border-slate-100">
            <p class="text-sm text-slate-500 mb-2">Status Pembayaran</p>
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium 
                <?php
                switch($order['payment_status']) {
                    case 'paid': echo 'bg-emerald-100 text-emerald-700'; break;
                    case 'pending': echo 'bg-amber-100 text-amber-700'; break;
                    case 'failed': echo 'bg-red-100 text-red-700'; break;
                    case 'expired': echo 'bg-red-100 text-red-700'; break;
                    case 'cancelled': echo 'bg-slate-100 text-slate-700'; break;
                    default: echo 'bg-slate-100 text-slate-700';
                }
                ?>">
                <?= ucfirst($order['payment_status']) ?>
            </span>
        </div>
        <?php endif; ?>

        <!-- Actions -->
        <div class="mt-6 flex flex-col sm:flex-row gap-3">
            <a href="<?= base_url('/orders') ?>" class="flex-1 sm:flex-none px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-colors text-center">
                Kembali ke Riwayat
            </a>
            <?php if (($order['payment_status'] === 'pending' || $order['payment_status'] === null) && $order['order_status'] === 'pending'): ?>
            <a href="<?= base_url('payment/process/' . $order['id']) ?>" class="flex-1 sm:flex-none px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-xl transition-colors text-center inline-flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                <span>Bayar Sekarang</span>
            </a>
            <?php else: ?>
            <a href="<?= base_url('/shop') ?>" class="flex-1 sm:flex-none px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-xl transition-colors text-center">
                Lanjut Berbelanja
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

