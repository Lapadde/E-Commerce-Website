<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-6 sm:mb-8">Checkout</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        <!-- Checkout Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 lg:p-8">
                <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-4 sm:mb-6">Informasi Pengiriman</h2>

                <form action="<?= base_url('/checkout/process') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="space-y-4 sm:space-y-6">
                        <div class="bg-slate-50 rounded-xl p-3 sm:p-4">
                            <p class="text-xs sm:text-sm text-slate-600 mb-2"><strong>Nama:</strong> <?= esc($customer['full_name'] ?? session()->get('customer_name')) ?></p>
                            <p class="text-xs sm:text-sm text-slate-600"><strong>Email:</strong> <?= esc($customer['email'] ?? session()->get('customer_email')) ?></p>
                        </div>

                        <div>
                            <label for="phone" class="block text-xs sm:text-sm font-semibold text-slate-700 mb-2">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="phone" 
                                   name="phone" 
                                   value="<?= old('phone', $customer['phone'] ?? '') ?>"
                                   class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm sm:text-base"
                                   required>
                            <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['phone'])): ?>
                                <p class="mt-1 text-xs sm:text-sm text-red-600"><?= session()->getFlashdata('errors')['phone'] ?></p>
                            <?php endif; ?>
                        </div>

                        <div>
                            <label for="shipping_address" class="block text-xs sm:text-sm font-semibold text-slate-700 mb-2">
                                Alamat Pengiriman <span class="text-red-500">*</span>
                            </label>
                            <textarea id="shipping_address" 
                                      name="shipping_address" 
                                      rows="4"
                                      class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm sm:text-base"
                                      required><?= old('shipping_address') ?></textarea>
                            <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['shipping_address'])): ?>
                                <p class="mt-1 text-xs sm:text-sm text-red-600"><?= session()->getFlashdata('errors')['shipping_address'] ?></p>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="w-full px-4 sm:px-6 py-2.5 sm:py-3 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-xl transition-colors text-sm sm:text-base">
                            Buat Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 lg:sticky lg:top-24">
                <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-4 sm:mb-6">Ringkasan Pesanan</h2>
                
                <div class="space-y-3 sm:space-y-4 mb-4 sm:mb-6">
                    <?php foreach ($cartItems as $item): ?>
                    <div class="flex justify-between items-start pb-3 sm:pb-4 border-b border-slate-200">
                        <div class="flex-1 min-w-0 pr-2">
                            <p class="font-medium text-slate-900 text-sm sm:text-base"><?= esc($item['product']['name']) ?></p>
                            <p class="text-xs sm:text-sm text-slate-500"><?= $item['quantity'] ?>x Rp <?= number_format($item['product']['price'], 0, ',', '.') ?></p>
                        </div>
                        <p class="font-semibold text-slate-900 text-sm sm:text-base flex-shrink-0">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>

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
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

