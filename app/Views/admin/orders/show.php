<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <a href="<?= base_url('admin/orders') ?>" class="inline-flex items-center text-slate-600 hover:text-slate-900 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Daftar Pesanan
    </a>
</div>

<!-- Order Header Card (Mobile Optimized) -->
<div class="bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl p-4 sm:p-6 mb-6 text-white shadow-xl shadow-primary-500/25">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-primary-100 text-sm mb-1">No. Pesanan</p>
            <h1 class="text-xl sm:text-2xl font-bold">#<?= $order['id'] ?></h1>
            <p class="text-primary-100 text-sm mt-2"><?= date('d M Y, H:i', strtotime($order['order_date'] ?? $order['created_at'] ?? 'now')) ?> WIB</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <!-- Order Status Badge -->
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-white/20 text-white backdrop-blur-sm">
                <span class="w-2 h-2 rounded-full mr-2 bg-white"></span>
                <?= ucfirst($order['order_status']) ?>
            </span>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Order Details (Left Column) -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Customer Info (Mobile - Show first) -->
        <div class="lg:hidden bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="p-4 border-b border-slate-100">
                <h2 class="font-bold text-slate-900">Informasi Pelanggan</h2>
            </div>
            <div class="p-4">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white text-lg font-semibold">
                        <?= substr($order['customer_name'], 0, 1) ?>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-slate-900"><?= $order['customer_name'] ?></p>
                        <p class="text-sm text-slate-500 truncate"><?= $order['customer_email'] ?></p>
                    </div>
                </div>
                <?php if (!empty($order['customer_phone'])): ?>
                <a href="tel:<?= $order['customer_phone'] ?>" class="flex items-center space-x-3 p-3 bg-slate-50 rounded-xl text-slate-700 hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <span class="font-medium"><?= $order['customer_phone'] ?></span>
                </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-slate-100">
                <h2 class="text-lg font-bold text-slate-900">Item Pesanan</h2>
            </div>
            <div class="divide-y divide-slate-100">
                <?php if (empty($items)): ?>
                <div class="p-6 text-center text-slate-500">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <p>Tidak ada item</p>
                </div>
                <?php else: ?>
                <?php foreach ($items as $item): ?>
                <div class="p-4 sm:p-6">
                    <div class="flex items-start space-x-4">
                        <!-- Product Image -->
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-slate-100 rounded-xl overflow-hidden flex-shrink-0">
                            <?php if (!empty($item['product_image'])): ?>
                            <img src="<?= base_url('uploads/products/' . $item['product_image']) ?>" alt="<?= $item['product_name'] ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Product Details -->
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-slate-900 mb-1"><?= $item['product_name'] ?></p>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-slate-500">
                                <span>Rp <?= number_format($item['price'], 0, ',', '.') ?></span>
                                <span>×</span>
                                <span><?= $item['quantity'] ?> item</span>
                            </div>
                        </div>
                        
                        <!-- Subtotal -->
                        <div class="text-right">
                            <p class="font-bold text-slate-900">Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Order Summary -->
            <div class="p-4 sm:p-6 bg-slate-50 border-t border-slate-100">
                <div class="space-y-3">
                    <div class="flex justify-between text-lg font-bold pt-3 border-t border-slate-200">
                        <span class="text-slate-900">Total</span>
                        <span class="text-primary-600">Rp <?= number_format($order['amount'], 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Info -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-slate-100">
                <h2 class="text-lg font-bold text-slate-900">Informasi Pesanan</h2>
            </div>
            <div class="p-4 sm:p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-sm text-slate-500 mb-1">No. Pesanan</p>
                        <p class="font-semibold text-slate-900">#<?= $order['id'] ?></p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-sm text-slate-500 mb-1">Tanggal Pesanan</p>
                        <p class="font-semibold text-slate-900"><?= date('d M Y, H:i', strtotime($order['order_date'] ?? $order['created_at'] ?? 'now')) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Address (Show on mobile too) -->
        <?php if (!empty($order['shipping_address'])): ?>
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-slate-100 flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Alamat Pengiriman</h2>
            </div>
            <div class="p-4 sm:p-6">
                <p class="text-slate-600 leading-relaxed"><?= nl2br($order['shipping_address']) ?></p>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <!-- Sidebar (Right Column) -->
    <div class="space-y-6">
        <!-- Customer Info (Desktop Only) -->
        <div class="hidden lg:block bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h2 class="text-lg font-bold text-slate-900">Informasi Pelanggan</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white text-lg font-semibold">
                        <?= substr($order['customer_name'], 0, 1) ?>
                    </div>
                    <div>
                        <p class="font-medium text-slate-900"><?= $order['customer_name'] ?></p>
                        <p class="text-sm text-slate-500"><?= $order['customer_email'] ?></p>
                    </div>
                </div>
                <?php if (!empty($order['customer_phone'])): ?>
                <div class="flex items-center space-x-3 p-3 bg-slate-50 rounded-xl text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <span><?= $order['customer_phone'] ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Order Status Update -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-slate-100">
                <h2 class="text-lg font-bold text-slate-900">Ubah Status</h2>
            </div>
            <div class="p-4 sm:p-6 space-y-4">
                <form action="<?= base_url('admin/orders/update-status/' . $order['id']) ?>" method="POST">
                    <?= csrf_field() ?>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Status Pesanan</label>
                    <select name="order_status" onchange="this.form.submit()"
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none text-base bg-white">
                        <option value="pending" <?= $order['order_status'] == 'pending' ? 'selected' : '' ?>>⏳ Pending</option>
                        <option value="processing" <?= $order['order_status'] == 'processing' ? 'selected' : '' ?>>🔄 Processing</option>
                        <option value="shipped" <?= $order['order_status'] == 'shipped' ? 'selected' : '' ?>>📦 Shipped</option>
                        <option value="delivered" <?= $order['order_status'] == 'delivered' ? 'selected' : '' ?>>✅ Delivered</option>
                        <option value="cancelled" <?= $order['order_status'] == 'cancelled' ? 'selected' : '' ?>>❌ Cancelled</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-slate-100">
                <h2 class="text-lg font-bold text-slate-900">Aksi Cepat</h2>
            </div>
            <div class="p-4 sm:p-6 space-y-3">
                <button onclick="window.print()" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Cetak Invoice</span>
                </button>
                <?php if (!empty($order['customer_phone'])): ?>
                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $order['customer_phone']) ?>" target="_blank" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 font-medium rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    <span>Hubungi WhatsApp</span>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
