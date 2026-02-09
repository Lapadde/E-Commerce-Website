<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-6 sm:mb-8">Keranjang Belanja</h1>

    <?php if (empty($cartItems)): ?>
    <div class="bg-white rounded-xl shadow-md p-8 sm:p-12 text-center">
        <svg class="w-16 h-16 sm:w-20 sm:h-20 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-2">Keranjang Anda Kosong</h3>
        <p class="text-sm sm:text-base text-slate-500 mb-6">Mulai berbelanja dan tambahkan produk ke keranjang</p>
        <a href="<?= base_url('/shop') ?>" class="inline-flex items-center px-5 sm:px-6 py-2.5 sm:py-3 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-lg transition-colors text-sm sm:text-base">
            Mulai Berbelanja
        </a>
    </div>
    <?php else: ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2 space-y-3 sm:space-y-4">
            <?php foreach ($cartItems as $item): ?>
            <div class="bg-white rounded-xl shadow-md p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                    <!-- Product Image -->
                    <div class="w-full sm:w-28 lg:w-32 h-28 lg:h-32 bg-slate-100 rounded-lg overflow-hidden flex-shrink-0">
                        <?php if ($item['product']['image']): ?>
                        <img src="<?= base_url('uploads/products/' . $item['product']['image']) ?>" 
                             alt="<?= esc($item['product']['name']) ?>" 
                             class="w-full h-full object-cover">
                        <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Product Info -->
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-base sm:text-lg text-slate-900 mb-2"><?= esc($item['product']['name']) ?></h3>
                        <p class="text-xs sm:text-sm text-slate-500 mb-3 sm:mb-4">SKU: <?= esc($item['product']['sku']) ?></p>
                        
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <p class="text-lg sm:text-xl font-bold text-primary-600">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></p>
                                <p class="text-xs sm:text-sm text-slate-500">Rp <?= number_format($item['product']['price'], 0, ',', '.') ?> x <?= $item['quantity'] ?></p>
                            </div>

                            <div class="flex items-center gap-3 sm:gap-4">
                                <form action="<?= base_url('/cart/update') ?>" method="POST" class="flex items-center border border-slate-300 rounded-lg">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="product_id" value="<?= $item['product']['id'] ?>">
                                    <button type="button" onclick="decreaseQty(<?= $item['product']['id'] ?>)" class="px-2 sm:px-3 py-2 text-slate-600 hover:bg-slate-100 text-sm sm:text-base">-</button>
                                    <input type="number" 
                                           id="qty-<?= $item['product']['id'] ?>" 
                                           name="quantity" 
                                           value="<?= $item['quantity'] ?>" 
                                           min="1" 
                                           max="<?= $item['product']['stock'] ?>" 
                                           onchange="this.form.submit()"
                                           class="w-14 sm:w-16 px-2 py-2 text-center border-0 focus:ring-0 text-sm sm:text-base">
                                    <button type="button" onclick="increaseQty(<?= $item['product']['id'] ?>, <?= $item['product']['stock'] ?>)" class="px-2 sm:px-3 py-2 text-slate-600 hover:bg-slate-100 text-sm sm:text-base">+</button>
                                </form>

                                <button type="button"
                                        onclick="removeProduct(<?= $item['product']['id'] ?>, '<?= esc($item['product']['name'], 'js') ?>')"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <div class="flex justify-end">
                <button type="button"
                        onclick="clearCart()"
                        class="text-sm sm:text-base text-red-600 hover:text-red-700 font-medium">
                    Hapus Semua
                </button>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 lg:sticky lg:top-24">
                <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-4 sm:mb-6">Ringkasan Pesanan</h2>
                
                <div class="space-y-3 sm:space-y-4 mb-4 sm:mb-6">
                    <div class="flex justify-between text-sm sm:text-base text-slate-600">
                        <span>Subtotal</span>
                        <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between text-sm sm:text-base text-slate-600">
                        <span>Ongkir</span>
                        <span>Rp 0</span>
                    </div>
                    <div class="border-t border-slate-200 pt-3 sm:pt-4">
                        <div class="flex justify-between text-base sm:text-lg font-bold text-slate-900">
                            <span>Total</span>
                            <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
                        </div>
                    </div>
                </div>

                <a href="<?= base_url('/checkout') ?>" class="block w-full px-4 sm:px-6 py-2.5 sm:py-3 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-lg text-center transition-colors text-sm sm:text-base">
                    Checkout
                </a>

                <a href="<?= base_url('/shop') ?>" class="block w-full mt-3 sm:mt-4 px-4 sm:px-6 py-2.5 sm:py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg text-center transition-colors text-sm sm:text-base">
                    Lanjut Belanja
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
    function increaseQty(productId, maxStock) {
        const qtyInput = document.getElementById('qty-' + productId);
        const current = parseInt(qtyInput.value);
        if (current < maxStock) {
            qtyInput.value = current + 1;
            qtyInput.form.submit();
        }
    }

    function decreaseQty(productId) {
        const qtyInput = document.getElementById('qty-' + productId);
        const current = parseInt(qtyInput.value);
        if (current > 1) {
            qtyInput.value = current - 1;
            qtyInput.form.submit();
        }
    }

    function removeProduct(productId, productName) {
        Swal.fire({
            icon: 'warning',
            title: 'Hapus Produk?',
            html: `Yakin ingin menghapus <strong>${productName}</strong> dari keranjang?`,
            showCancelButton: true,
            confirmButtonColor: '#f15a47',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            position: 'center',
            width: window.innerWidth <= 640 ? '90%' : '500px',
            customClass: {
                popup: 'swal2-popup-responsive',
                title: 'swal2-title-responsive',
                htmlContainer: 'swal2-html-container-responsive',
                confirmButton: 'swal2-confirm-responsive',
                cancelButton: 'swal2-cancel-responsive'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to remove URL
                window.location.href = '<?= base_url('/cart/remove/') ?>' + productId;
            }
        });
    }

    function clearCart() {
        Swal.fire({
            icon: 'warning',
            title: 'Kosongkan Keranjang?',
            text: 'Yakin ingin mengosongkan semua produk dari keranjang?',
            showCancelButton: true,
            confirmButtonColor: '#f15a47',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Kosongkan',
            cancelButtonText: 'Batal',
            position: 'center',
            width: window.innerWidth <= 640 ? '90%' : '500px',
            customClass: {
                popup: 'swal2-popup-responsive',
                title: 'swal2-title-responsive',
                htmlContainer: 'swal2-html-container-responsive',
                confirmButton: 'swal2-confirm-responsive',
                cancelButton: 'swal2-cancel-responsive'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to clear URL
                window.location.href = '<?= base_url('/cart/clear') ?>';
            }
        });
    }
</script>
<?= $this->endSection() ?>

