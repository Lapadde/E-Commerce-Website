<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-500 to-primary-700 rounded-2xl p-4 sm:p-6 md:p-8 lg:p-12 mb-6 sm:mb-8 text-white">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-3 sm:mb-4">Selamat Datang di Toko Tunjuk Tunjuk</h1>
        <p class="text-base sm:text-lg md:text-xl text-white/90 mb-4 sm:mb-6">Temukan produk terbaik dengan harga dan kualitas terbaik</p>
        
        <!-- Search Bar -->
        <div class="max-w-2xl">
            <div class="relative">
                <input type="text" 
                       id="search_input" 
                       name="search" 
                       value="<?= esc($search ?? '') ?>" 
                       placeholder="Cari produk..." 
                       autocomplete="off"
                       class="w-full px-4 sm:px-6 py-2.5 sm:py-3 pr-12 rounded-xl text-slate-900 focus:ring-2 focus:ring-white focus:outline-none text-sm sm:text-base">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg id="search_icon" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <svg id="search_loading" class="w-5 h-5 text-white hidden animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-4 sm:gap-6">
        <!-- Category Filter Dropdown -->
        <div class="bg-white rounded-xl shadow-md p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex-1 sm:max-w-xs">
                    <label for="category_filter" class="block text-sm font-medium text-slate-700 mb-2">
                        Kategori
                    </label>
                    <div class="relative">
                        <select id="category_filter" 
                                name="category" 
                                class="w-full px-4 py-3 pr-10 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors text-sm sm:text-base bg-white appearance-none cursor-pointer">
                            <option value="">Semua Kategori</option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= $categoryId == $category['id'] ? 'selected' : '' ?>>
                                <?= esc($category['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Results Count -->
                <div id="results_count" class="text-sm text-slate-600 self-end sm:self-center">
                    Menampilkan <?= count($products) ?> dari <?= number_format($total, 0, ',', '.') ?> produk
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="flex-1" id="products_container">
            <?php if (empty($products)): ?>
            <div class="bg-white rounded-xl shadow-md p-8 sm:p-12 text-center" id="empty_state">
                <svg class="w-12 h-12 sm:w-16 sm:h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-2">Produk tidak ditemukan</h3>
                <p class="text-sm sm:text-base text-slate-500">Coba cari dengan kata kunci lain</p>
            </div>
            <?php else: ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6" id="products_grid">
                <?php foreach ($products as $product): ?>
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow group">
                    <a href="<?= base_url('/shop/' . $product['id']) ?>">
                        <div class="aspect-square bg-slate-100 relative overflow-hidden">
                            <?php if ($product['image']): ?>
                            <img src="<?= base_url('uploads/products/' . $product['image']) ?>" 
                                 alt="<?= esc($product['name']) ?>" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($product['stock'] <= 10): ?>
                            <div class="absolute top-3 right-3 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                Stok Terbatas
                            </div>
                            <?php endif; ?>
                        </div>
                    </a>
                    
                    <div class="p-3 sm:p-4">
                        <a href="<?= base_url('/shop/' . $product['id']) ?>">
                            <h3 class="font-semibold text-slate-900 mb-2 line-clamp-2 group-hover:text-primary-600 transition-colors text-sm sm:text-base">
                                <?= esc($product['name']) ?>
                            </h3>
                        </a>
                        
                        <div class="flex items-center justify-between mt-3 sm:mt-4">
                            <div>
                                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-primary-600">Rp <?= number_format($product['price'], 0, ',', '.') ?></p>
                                <p class="text-xs sm:text-sm text-slate-500">Stok: <?= $product['stock'] ?></p>
                            </div>
                            
                            <form action="<?= base_url('/cart/add') ?>" method="POST" class="inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="p-2 sm:px-3 sm:py-2 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-lg transition-colors">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php endif; ?>
            
            <!-- Pagination Container -->
            <div id="pagination_container">
                <?php if ($total > $perPage): ?>
                <div class="mt-6 sm:mt-8 flex justify-center">
                    <div class="flex items-center space-x-1 sm:space-x-2">
                        <?php 
                        $totalPages = ceil($total / $perPage);
                        $startPage = max(1, $page - 2);
                        $endPage = min($totalPages, $page + 2);
                        
                        // Previous
                        if ($page > 1): 
                            $prevUrl = base_url('/shop') . '?page=' . ($page - 1) . ($search ? '&search=' . urlencode($search) : '') . ($categoryId ? '&category=' . $categoryId : '');
                        ?>
                        <a href="<?= $prevUrl ?>" class="px-3 sm:px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <?php endif; ?>
                        
                        <?php for ($i = $startPage; $i <= $endPage; $i++): 
                            $pageUrl = base_url('/shop') . '?page=' . $i . ($search ? '&search=' . urlencode($search) : '') . ($categoryId ? '&category=' . $categoryId : '');
                        ?>
                        <a href="<?= $pageUrl ?>" class="px-3 sm:px-4 py-2 text-xs sm:text-sm <?= $i == $page ? 'bg-primary-500 text-white' : 'bg-white border border-slate-300 text-slate-700 hover:bg-slate-50' ?> rounded-lg">
                            <?= $i ?>
                        </a>
                        <?php endfor; ?>
                        
                        <?php if ($page < $totalPages): 
                            $nextUrl = base_url('/shop') . '?page=' . ($page + 1) . ($search ? '&search=' . urlencode($search) : '') . ($categoryId ? '&category=' . $categoryId : '');
                        ?>
                        <a href="<?= $nextUrl ?>" class="px-3 sm:px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search_input');
    const searchIcon = document.getElementById('search_icon');
    const searchLoading = document.getElementById('search_loading');
    const categoryFilter = document.getElementById('category_filter');
    const productsContainer = document.getElementById('products_container');
    const resultsCount = document.getElementById('results_count');
    let searchTimeout;
    
    // Debounce function
    function debounce(func, wait) {
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(searchTimeout);
                func(...args);
            };
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(later, wait);
        };
    }
    
    // Format number (same as PHP number_format with 0 decimals, '.' as thousands separator)
    function formatNumber(num) {
        // Convert to number and remove decimals (same as PHP number_format with 0 decimals)
        const numValue = Math.floor(parseFloat(num) || 0);
        // Format with dot as thousands separator (same as PHP: '.')
        return numValue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    // Format currency (exactly same format as PHP: "Rp 1.000.000")
    function formatCurrency(amount) {
        // Ensure we format exactly like PHP: number_format($price, 0, ',', '.')
        // Result: "Rp " + formatted_number
        const formatted = formatNumber(amount);
        return 'Rp ' + formatted;
    }
    
    // Get CSRF token from meta tag
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }
    
    // Perform search
    function performSearch(query) {
        const categoryId = categoryFilter ? categoryFilter.value : '';
        
        // Show loading
        if (searchIcon) searchIcon.classList.add('hidden');
        if (searchLoading) searchLoading.classList.remove('hidden');
        
        // Build URL
        const url = new URL('<?= base_url('/shop/search') ?>', window.location.origin);
        if (query) {
            url.searchParams.set('q', query);
        }
        if (categoryId) {
            url.searchParams.set('category', categoryId);
        }
        url.searchParams.set('page', '1');
        
        // Fetch data
        fetch(url.toString())
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateProducts(data.products, data.total);
                } else {
                    console.error('Search failed');
                }
            })
            .catch(error => {
                console.error('Search error:', error);
            })
            .finally(() => {
                // Hide loading
                if (searchIcon) searchIcon.classList.remove('hidden');
                if (searchLoading) searchLoading.classList.add('hidden');
            });
    }
    
    // Update products display
    function updateProducts(products, total) {
        const productsGrid = document.getElementById('products_grid');
        const emptyState = document.getElementById('empty_state');
        const paginationContainer = document.getElementById('pagination_container');
        
        // Update results count
        if (resultsCount) {
            resultsCount.textContent = `Menampilkan ${products.length} dari ${formatNumber(total)} produk`;
        }
        
        // Clear existing content
        if (productsGrid) {
            productsGrid.innerHTML = '';
        }
        if (emptyState) {
            emptyState.style.display = 'none';
        }
        if (paginationContainer) {
            paginationContainer.innerHTML = '';
        }
        
        if (products.length === 0) {
            // Show empty state
            if (emptyState) {
                emptyState.style.display = 'block';
            }
        } else {
            // Show products grid
            if (!productsGrid) {
                const grid = document.createElement('div');
                grid.id = 'products_grid';
                grid.className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6';
                productsContainer.appendChild(grid);
            }
            
            // Render products
            products.forEach(product => {
                const productCard = createProductCard(product);
                if (productsGrid) {
                    productsGrid.appendChild(productCard);
                }
            });
        }
    }
    
    // Create product card element
    function createProductCard(product) {
        const card = document.createElement('div');
        card.className = 'bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow group';
        
        const imageUrl = product.image ? '<?= base_url('uploads/products/') ?>' + product.image : '';
        const productUrl = '<?= base_url('/shop/') ?>' + product.id;
        const stockBadge = product.stock <= 10 ? '<div class="absolute top-3 right-3 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-semibold">Stok Terbatas</div>' : '';
        
        card.innerHTML = `
            <a href="${productUrl}">
                <div class="aspect-square bg-slate-100 relative overflow-hidden">
                    ${product.image ? 
                        `<img src="${imageUrl}" alt="${product.name.replace(/"/g, '&quot;')}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">` :
                        `<div class="w-full h-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>`
                    }
                    ${stockBadge}
                </div>
            </a>
            <div class="p-3 sm:p-4">
                <a href="${productUrl}">
                    <h3 class="font-semibold text-slate-900 mb-2 line-clamp-2 group-hover:text-primary-600 transition-colors text-sm sm:text-base">
                        ${product.name.replace(/</g, '&lt;').replace(/>/g, '&gt;')}
                    </h3>
                </a>
                <div class="flex items-center justify-between mt-3 sm:mt-4">
                    <div>
                        <p class="text-lg sm:text-xl lg:text-2xl font-bold text-primary-600">${formatCurrency(product.price)}</p>
                        <p class="text-xs sm:text-sm text-slate-500">Stok: ${product.stock}</p>
                    </div>
                    <form action="<?= base_url('/cart/add') ?>" method="POST" class="inline">
                        <input type="hidden" name="<?= csrf_token() ?>" value="${getCsrfToken()}">
                        <input type="hidden" name="product_id" value="${product.id}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="p-2 sm:px-3 sm:py-2 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-lg transition-colors">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        `;
        
        return card;
    }
    
    // Real-time search with debounce
    if (searchInput) {
        const debouncedSearch = debounce(function(query) {
            if (query.length >= 2 || query.length === 0) {
                performSearch(query);
            }
        }, 500); // 500ms delay
        
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            debouncedSearch(query);
        });
        
        // Also trigger on Enter key
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = this.value.trim();
                performSearch(query);
            }
        });
    }
    
    // Category filter change
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            const categoryId = this.value;
            const searchQuery = searchInput ? searchInput.value.trim() : '';
            const currentUrl = new URL(window.location.href);
            
            // Update URL parameters
            if (categoryId) {
                currentUrl.searchParams.set('category', categoryId);
            } else {
                currentUrl.searchParams.delete('category');
            }
            
            if (searchQuery) {
                currentUrl.searchParams.set('search', searchQuery);
            } else {
                currentUrl.searchParams.delete('search');
            }
            
            // Reset to page 1 when changing category
            currentUrl.searchParams.set('page', '1');
            
            // Perform search if there's a query
            if (searchQuery) {
                performSearch(searchQuery);
            } else {
                // Redirect to new URL
                window.location.href = currentUrl.toString();
            }
        });
    }
});
</script>
<?= $this->endSection() ?>