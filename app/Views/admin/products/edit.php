<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <a href="<?= base_url('admin/products') ?>" class="inline-flex items-center text-slate-600 hover:text-slate-900 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Daftar Produk
    </a>
</div>

<div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
    <div class="p-4 sm:p-6 border-b border-slate-100">
        <h2 class="text-lg font-bold text-slate-900">Edit Produk</h2>
        <p class="text-sm text-slate-500 mt-1">Ubah detail produk</p>
    </div>
    
    <form action="<?= base_url('admin/products/update/' . $product['id']) ?>" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
        <?= csrf_field() ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
            <!-- Left Column -->
            <div class="space-y-5">
                <div>
                    <label for="sku" class="block text-sm font-medium text-slate-700 mb-2">SKU <span class="text-red-500">*</span></label>
                    <input type="text" id="sku" name="sku" value="<?= old('sku') ?? $product['sku'] ?>" required
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none text-base"
                        placeholder="SKU-001">
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="<?= old('name') ?? $product['name'] ?>" required
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none text-base"
                        placeholder="Masukkan nama produk">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                    <div class="bg-slate-50 rounded-xl p-4 space-y-3 max-h-48 overflow-y-auto">
                        <?php foreach ($categories as $category): ?>
                        <label class="flex items-center space-x-3 cursor-pointer p-2 -mx-2 rounded-lg hover:bg-slate-100 transition-colors">
                            <input type="checkbox" name="categories[]" value="<?= $category['id'] ?>" <?= (old('categories') && in_array($category['id'], old('categories'))) || (isset($product['category_ids']) && in_array($category['id'], $product['category_ids'])) ? 'checked' : '' ?>
                                class="w-5 h-5 text-primary-600 border-slate-300 rounded focus:ring-primary-500">
                            <div>
                                <span class="text-sm text-slate-900 font-medium"><?= $category['name'] ?></span>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Deskripsi</label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none resize-none text-base"
                        placeholder="Masukkan deskripsi produk"><?= old('description') ?? $product['description'] ?></textarea>
                </div>

                <!-- Image Upload -->
                <div>
                    <label for="image" class="block text-sm font-medium text-slate-700 mb-2">Gambar Produk</label>
                    <div id="dropZone" class="border-2 border-dashed border-slate-200 rounded-xl p-4 sm:p-6 text-center hover:border-primary-400 transition-colors bg-slate-50/50 relative">
                        <input type="file" id="image" name="image" accept="image/*" class="hidden" onchange="handleFileSelect(this.files)">
                        <label for="image" class="cursor-pointer block">
                            <div id="imagePreview" class="<?= $product['image'] ? '' : 'hidden' ?> mb-4">
                                <img id="preview" src="<?= $product['image'] ? base_url('uploads/products/' . $product['image']) : '' ?>" alt="Preview" class="max-h-48 sm:max-h-40 mx-auto rounded-lg shadow-lg">
                                <p class="text-xs text-slate-500 mt-2">Klik untuk mengganti gambar</p>
                                <button type="button" onclick="removeImage()" class="mt-2 text-xs text-red-600 hover:text-red-700 font-medium">
                                    Hapus Gambar
                                </button>
                            </div>
                            <div id="uploadPlaceholder" class="<?= $product['image'] ? 'hidden' : '' ?>">
                                <div class="w-16 h-16 bg-slate-200 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-slate-600 font-medium">Drag & drop gambar di sini</p>
                                <p class="text-slate-600 font-medium mt-1">atau <span class="text-primary-600 underline">klik untuk memilih</span></p>
                                <p class="text-xs text-slate-400 mt-1">PNG, JPG, JPEG (Max. 2MB)</p>
                            </div>
                        </label>
                        <div id="dropOverlay" class="hidden absolute inset-0 bg-primary-500/10 border-2 border-dashed border-primary-500 rounded-xl flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-12 h-12 text-primary-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-primary-600 font-semibold">Lepaskan untuk upload</p>
                            </div>
                        </div>
                    </div>
                    <div id="errorMessage" class="hidden mt-2 text-sm text-red-600"></div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-5">
                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-slate-700 mb-2">Harga <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium">Rp</span>
                        <input type="number" id="price" name="price" value="<?= old('price') ?? number_format($product['price'], 0, '.', '') ?>" required
                            step="0.01" min="0"
                            class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none text-base"
                            placeholder="0">
                    </div>
                </div>

                <!-- Stock & Weight -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="stock" class="block text-sm font-medium text-slate-700 mb-2">Stok <span class="text-red-500">*</span></label>
                        <input type="number" id="stock" name="stock" value="<?= old('stock') ?? $product['stock'] ?>" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none text-base"
                            placeholder="0">
                    </div>
                    <div>
                        <label for="weight" class="block text-sm font-medium text-slate-700 mb-2">Berat (gram)</label>
                        <input type="number" id="weight" name="weight" value="<?= old('weight') ?? $product['weight'] ?>" step="0.01"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none text-base"
                            placeholder="0">
                    </div>
                </div>

                <!-- Current Product Info -->
                <div class="bg-blue-50 rounded-xl p-4">
                    <p class="text-sm font-medium text-blue-900 mb-2">Informasi Produk</p>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <span class="text-blue-600">ID Produk:</span>
                            <span class="text-blue-900 font-medium">#<?= $product['id'] ?></span>
                        </div>
                        <div>
                            <span class="text-blue-600">Dibuat:</span>
                            <span class="text-blue-900 font-medium"><?= date('d/m/Y', strtotime($product['created_at'])) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 mt-8 pt-6 border-t border-slate-100">
            <a href="<?= base_url('admin/products') ?>" class="w-full sm:w-auto px-6 py-3 text-center text-slate-600 hover:text-slate-900 font-medium transition-colors border border-slate-200 rounded-xl hover:bg-slate-50">
                Batal
            </a>
            <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('image');
const imagePreview = document.getElementById('imagePreview');
const uploadPlaceholder = document.getElementById('uploadPlaceholder');
const preview = document.getElementById('preview');
const dropOverlay = document.getElementById('dropOverlay');
const errorMessage = document.getElementById('errorMessage');

// Drag and Drop Events
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    e.stopPropagation();
    dropZone.classList.add('border-primary-500', 'bg-primary-50/50');
    dropOverlay.classList.remove('hidden');
});

dropZone.addEventListener('dragleave', (e) => {
    e.preventDefault();
    e.stopPropagation();
    dropZone.classList.remove('border-primary-500', 'bg-primary-50/50');
    dropOverlay.classList.add('hidden');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    e.stopPropagation();
    dropZone.classList.remove('border-primary-500', 'bg-primary-50/50');
    dropOverlay.classList.add('hidden');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        handleFileSelect(files);
    }
});

// File input change
fileInput.addEventListener('change', function() {
    handleFileSelect(this.files);
});

// Handle file selection
function handleFileSelect(files) {
    if (files.length === 0) return;
    
    const file = files[0];
    
    // Validate file type
    if (!file.type.match('image.*')) {
        showError('File harus berupa gambar (PNG, JPG, JPEG)');
        return;
    }
    
    // Validate file size (2MB = 2 * 1024 * 1024 bytes)
    if (file.size > 2 * 1024 * 1024) {
        showError('Ukuran file maksimal 2MB');
        return;
    }
    
    // Hide error message
    hideError();
    
    // Set file to input
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    fileInput.files = dataTransfer.files;
    
    // Preview image
    const reader = new FileReader();
    reader.onload = function(e) {
        preview.src = e.target.result;
        imagePreview.classList.remove('hidden');
        uploadPlaceholder.classList.add('hidden');
    };
    reader.readAsDataURL(file);
}

// Remove image
function removeImage() {
    fileInput.value = '';
    preview.src = '';
    imagePreview.classList.add('hidden');
    uploadPlaceholder.classList.remove('hidden');
    hideError();
}

// Show error message
function showError(message) {
    errorMessage.textContent = message;
    errorMessage.classList.remove('hidden');
    setTimeout(() => {
        hideError();
    }, 5000);
}

// Hide error message
function hideError() {
    errorMessage.classList.add('hidden');
}

// Legacy function for backward compatibility
function previewImage(input) {
    handleFileSelect(input.files);
}

// SweetAlert for validation errors
document.addEventListener('DOMContentLoaded', function() {
    <?php if (session()->getFlashdata('errors')): ?>
    const errors = <?= json_encode(session()->getFlashdata('errors'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const errorMessages = Object.values(errors).flat();
    
    Swal.fire({
        icon: 'error',
        title: 'Validasi Gagal',
        html: '<div class="text-left"><p class="mb-2 font-medium">Terjadi kesalahan pada form:</p><ul class="list-disc list-inside space-y-1">' + 
              errorMessages.map(msg => '<li>' + msg + '</li>').join('') + 
              '</ul></div>',
        confirmButtonColor: '#f15a47',
        confirmButtonText: 'OK',
        customClass: {
            popup: 'swal2-popup-responsive',
            title: 'swal2-title-responsive',
            htmlContainer: 'swal2-html-container-responsive',
            confirmButton: 'swal2-confirm-responsive'
        },
        buttonsStyling: false
    });
    <?php endif; ?>
});
</script>
<?= $this->endSection() ?>
