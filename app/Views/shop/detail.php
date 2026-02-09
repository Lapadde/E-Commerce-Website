<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <a href="<?= base_url('/shop') ?>" class="inline-flex items-center text-slate-600 hover:text-primary-600 mb-4 sm:mb-6 text-sm sm:text-base">
        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Kembali ke Beranda
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 mb-8 sm:mb-12">
        <!-- Product Image -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="aspect-square bg-slate-100">
                <?php if ($product['image']): ?>
                <img src="<?= base_url('uploads/products/' . $product['image']) ?>" 
                     alt="<?= esc($product['name']) ?>" 
                     class="w-full h-full object-cover">
                <?php else: ?>
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-32 h-32 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Info -->
        <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 lg:p-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-3 sm:mb-4"><?= esc($product['name']) ?></h1>
            
            <div class="mb-4 sm:mb-6">
                <p class="text-3xl sm:text-4xl font-bold text-primary-600 mb-2">Rp <?= number_format($product['price'], 0, ',', '.') ?></p>
                <p class="text-xs sm:text-sm text-slate-500">SKU: <?= esc($product['sku']) ?></p>
            </div>

            <?php if ($product['description']): ?>
            <div class="mb-4 sm:mb-6">
                <h3 class="font-semibold text-slate-900 mb-2 text-sm sm:text-base">Deskripsi</h3>
                <p class="text-sm sm:text-base text-slate-600 leading-relaxed"><?= nl2br(esc($product['description'])) ?></p>
            </div>
            <?php endif; ?>

            <div class="mb-4 sm:mb-6">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <span class="font-semibold text-slate-900 text-sm sm:text-base">Stok Tersedia:</span>
                    <span class="text-base sm:text-lg font-bold <?= $product['stock'] > 10 ? 'text-emerald-600' : 'text-red-600' ?>">
                        <?= $product['stock'] ?> unit
                    </span>
                </div>

                <?php if ($product['stock'] > 0): ?>
                <form action="<?= base_url('/cart/add') ?>" method="POST" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
                    <?= csrf_field() ?>
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <div class="flex items-center border border-slate-300 rounded-lg w-full sm:w-auto">
                        <button type="button" onclick="decreaseQty()" class="px-3 sm:px-4 py-2 text-slate-600 hover:bg-slate-100 text-sm sm:text-base">-</button>
                        <input type="number" 
                               id="quantity" 
                               name="quantity" 
                               value="1" 
                               min="1" 
                               max="<?= $product['stock'] ?>" 
                               class="w-full sm:w-20 px-3 sm:px-4 py-2 text-center border-0 focus:ring-0 text-sm sm:text-base">
                        <button type="button" onclick="increaseQty()" class="px-3 sm:px-4 py-2 text-slate-600 hover:bg-slate-100 text-sm sm:text-base">+</button>
                    </div>
                    <button type="submit" class="flex-1 sm:flex-none px-6 sm:px-8 py-2.5 sm:py-3 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-lg transition-colors text-sm sm:text-base">
                        Tambah ke Keranjang
                    </button>
                </form>
                <?php else: ?>
                <button disabled class="w-full px-6 sm:px-8 py-2.5 sm:py-3 bg-slate-300 text-slate-500 font-semibold rounded-lg cursor-not-allowed text-sm sm:text-base">
                    Stok Habis
                </button>
                <?php endif; ?>
            </div>

            <?php if (!empty($product['categories'])): ?>
            <div>
                <h3 class="font-semibold text-slate-900 mb-2 text-sm sm:text-base">Kategori:</h3>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($product['categories'] as $category): ?>
                    <span class="px-2 sm:px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs sm:text-sm">
                        <?= esc($category['name']) ?>
                    </span>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
    <div class="mt-8 sm:mt-12">
        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mb-4 sm:mb-6">Produk Terkait</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <?php foreach ($relatedProducts as $related): ?>
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <a href="<?= base_url('/shop/' . $related['id']) ?>">
                    <div class="aspect-square bg-slate-100">
                        <?php if ($related['image']): ?>
                        <img src="<?= base_url('uploads/products/' . $related['image']) ?>" 
                             alt="<?= esc($related['name']) ?>" 
                             class="w-full h-full object-cover">
                        <?php endif; ?>
                    </div>
                </a>
                <div class="p-3 sm:p-4">
                    <a href="<?= base_url('/shop/' . $related['id']) ?>">
                        <h3 class="font-semibold text-slate-900 mb-2 line-clamp-2 text-sm sm:text-base"><?= esc($related['name']) ?></h3>
                    </a>
                    <p class="text-base sm:text-lg font-bold text-primary-600">Rp <?= number_format($related['price'], 0, ',', '.') ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
    function increaseQty() {
        const qtyInput = document.getElementById('quantity');
        const max = parseInt(qtyInput.getAttribute('max'));
        const current = parseInt(qtyInput.value);
        if (current < max) {
            qtyInput.value = current + 1;
        }
    }

    function decreaseQty() {
        const qtyInput = document.getElementById('quantity');
        const current = parseInt(qtyInput.value);
        if (current > 1) {
            qtyInput.value = current - 1;
        }
    }
</script>
<?= $this->endSection() ?>

