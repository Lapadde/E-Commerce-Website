<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-6 sm:mb-8">Pembayaran</h1>

    <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 lg:p-8 mb-6">
        <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-4">Detail Pesanan</h2>
        
        <div class="space-y-3 sm:space-y-4 mb-6">
            <div class="flex justify-between text-sm sm:text-base">
                <span class="text-slate-600">No. Pesanan:</span>
                <span class="font-semibold text-slate-900">#<?= $order['id'] ?></span>
            </div>
            <div class="flex justify-between text-sm sm:text-base">
                <span class="text-slate-600">Total Pembayaran:</span>
                <span class="font-bold text-primary-600 text-lg">Rp <?= number_format($order['amount'], 0, ',', '.') ?></span>
            </div>
        </div>

        <div class="border-t border-slate-200 pt-4">
            <h3 class="font-semibold text-slate-900 mb-3 text-sm sm:text-base">Item Pesanan:</h3>
            <div class="space-y-2">
                <?php foreach ($orderItems as $item): ?>
                <div class="flex justify-between text-xs sm:text-sm">
                    <span class="text-slate-600"><?= esc($item['product_name']) ?> (<?= $item['quantity'] ?>x)</span>
                    <span class="text-slate-900">Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 lg:p-8">
        <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-4">Pilih Metode Pembayaran</h2>
        <p class="text-sm text-slate-600 mb-6">Silakan pilih metode pembayaran yang Anda inginkan</p>
        
        <!-- Midtrans SNAP Container -->
        <div id="snap-container"></div>
    </div>
</div>

<!-- Midtrans SNAP Script -->
<?php 
$snapUrl = ($isProduction ?? false) ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
?>
<script src="<?= $snapUrl ?>" data-client-key="<?= esc($clientKey) ?>"></script>
<script>
    // Get snap token from PHP
    const snapToken = '<?= esc($snapToken) ?>';
    
    // Initialize SNAP
    snap.pay(snapToken, {
        onSuccess: function(result) {
            // Redirect to success page
            window.location.href = '<?= base_url('payment/finish') ?>?order_id=' + result.order_id;
        },
        onPending: function(result) {
            // Redirect to unfinish page
            window.location.href = '<?= base_url('payment/unfinish') ?>?order_id=' + result.order_id;
        },
        onError: function(result) {
            // Redirect to error page
            window.location.href = '<?= base_url('payment/error') ?>?order_id=' + result.order_id;
        },
        onClose: function() {
            // User closed the popup
            Swal.fire({
                icon: 'info',
                title: 'Pembayaran Dibatalkan',
                text: 'Pembayaran dibatalkan. Anda dapat mencoba lagi nanti.',
                confirmButtonColor: '#f15a47',
                confirmButtonText: 'OK',
                position: 'center',
                width: window.innerWidth <= 640 ? '90%' : '500px',
                customClass: {
                    popup: 'swal2-popup-responsive',
                    title: 'swal2-title-responsive',
                    htmlContainer: 'swal2-html-container-responsive',
                    confirmButton: 'swal2-confirm-responsive'
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>

