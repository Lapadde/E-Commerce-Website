<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Filter Section -->
<div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-4 sm:p-6 mb-4 sm:mb-6">
    <form method="GET" action="<?= base_url('admin/audit-logs') ?>" class="space-y-4">
        <?php if ($perPage ?? 5): ?>
        <input type="hidden" name="per_page" value="<?= $perPage ?>">
        <?php endif; ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
            <div class="sm:col-span-2 lg:col-span-1">
                <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Pencarian</label>
                <input type="text" 
                       name="search" 
                       value="<?= esc($search ?? '') ?>" 
                       placeholder="Nama, email, atau action..."
                       class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm sm:text-base">
            </div>
            <div>
                <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Action</label>
                <input type="text" 
                       name="action" 
                       value="<?= esc($action ?? '') ?>" 
                       placeholder="Filter action..."
                       class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm sm:text-base">
            </div>
            <div>
                <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Tanggal Mulai</label>
                <input type="date" 
                       name="start_date" 
                       value="<?= $startDate ?? '' ?>" 
                       class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm sm:text-base">
            </div>
            <div>
                <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Tanggal Akhir</label>
                <input type="date" 
                       name="end_date" 
                       value="<?= $endDate ?? '' ?>" 
                       class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm sm:text-base">
            </div>
            <div class="flex items-end sm:col-span-2 lg:col-span-1">
                <button type="submit" class="w-full px-4 sm:px-6 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25 text-sm sm:text-base">
                    Filter
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Stats -->
<div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-4 sm:p-6 mb-4 sm:mb-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
        <div class="w-full sm:w-auto">
            <h3 class="text-sm sm:text-base lg:text-lg font-bold text-slate-900 mb-1">Total Log: <?= number_format($total ?? 0) ?></h3>
            <p class="text-xs sm:text-sm text-slate-500">Menampilkan <?= count($logs ?? []) ?> dari <?= number_format($total ?? 0) ?> log</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 w-full sm:w-auto">
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <label for="per_page" class="text-xs sm:text-sm text-slate-600 whitespace-nowrap">Tampilkan:</label>
                <select id="per_page" name="per_page" onchange="changePerPage(this.value)" 
                        class="flex-1 sm:flex-none px-2 sm:px-3 py-1.5 sm:py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-xs sm:text-sm bg-white">
                    <?php foreach ($perPageOptions ?? [5, 10, 50, 100, 500] as $option): ?>
                    <option value="<?= $option ?>" <?= ($perPage ?? 5) == $option ? 'selected' : '' ?>>
                        <?= $option ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <a href="<?= base_url('admin/audit-logs') ?>" class="w-full sm:w-auto px-3 sm:px-4 py-1.5 sm:py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors text-center text-xs sm:text-sm">
                Reset Filter
            </a>
        </div>
    </div>
</div>

<!-- Logs Table -->
<div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
    <div class="p-4 sm:p-6 border-b border-slate-100">
        <h2 class="text-base sm:text-lg font-bold text-slate-900">Activity Log</h2>
    </div>
    
    <?php if (empty($logs)): ?>
    <div class="p-8 sm:p-12 text-center">
        <svg class="w-12 h-12 sm:w-16 sm:h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <p class="text-sm sm:text-base text-slate-500">Tidak ada log ditemukan</p>
    </div>
    <?php else: ?>
    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full min-w-full">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-3 sm:px-4 lg:px-6 py-2.5 sm:py-3 lg:py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Waktu</th>
                    <th class="px-3 sm:px-4 lg:px-6 py-2.5 sm:py-3 lg:py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">User</th>
                    <th class="px-3 sm:px-4 lg:px-6 py-2.5 sm:py-3 lg:py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($logs as $log): ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-3 sm:px-4 lg:px-6 py-2.5 sm:py-3 lg:py-4">
                        <div class="text-xs sm:text-sm font-medium text-slate-900">
                            <?= date('d/m/Y', strtotime($log['created_at'])) ?>
                        </div>
                        <div class="text-xs text-slate-500 mt-0.5">
                            <?= date('H:i:s', strtotime($log['created_at'])) ?>
                        </div>
                    </td>
                    <td class="px-3 sm:px-4 lg:px-6 py-2.5 sm:py-3 lg:py-4">
                        <div class="text-xs sm:text-sm font-medium text-slate-900 truncate max-w-xs">
                            <?= esc($log['full_name'] ?? 'Unknown') ?>
                        </div>
                        <div class="text-xs text-slate-500 truncate max-w-xs mt-0.5">
                            <?= esc($log['email'] ?? '') ?>
                        </div>
                    </td>
                    <td class="px-3 sm:px-4 lg:px-6 py-2.5 sm:py-3 lg:py-4">
                        <span class="inline-flex items-center px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full text-xs font-medium break-words max-w-xs 
                            <?php
                            $action = strtolower($log['action']);
                            if (strpos($action, 'login') !== false) {
                                echo 'bg-blue-100 text-blue-700';
                            } elseif (strpos($action, 'logout') !== false) {
                                echo 'bg-slate-100 text-slate-700';
                            } elseif (strpos($action, 'menambah') !== false || strpos($action, 'create') !== false) {
                                echo 'bg-emerald-100 text-emerald-700';
                            } elseif (strpos($action, 'mengupdate') !== false || strpos($action, 'update') !== false) {
                                echo 'bg-amber-100 text-amber-700';
                            } elseif (strpos($action, 'menghapus') !== false || strpos($action, 'delete') !== false) {
                                echo 'bg-red-100 text-red-700';
                            } elseif (strpos($action, 'pesanan') !== false || strpos($action, 'order') !== false) {
                                echo 'bg-violet-100 text-violet-700';
                            } else {
                                echo 'bg-slate-100 text-slate-700';
                            }
                            ?>">
                            <span class="break-words"><?= esc($log['action']) ?></span>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden divide-y divide-slate-100">
        <?php foreach ($logs as $log): ?>
        <div class="p-3 sm:p-4 hover:bg-slate-50 transition-colors">
            <div class="flex items-start justify-between mb-2 sm:mb-3">
                <div class="flex-1 min-w-0 pr-2">
                    <div class="text-xs sm:text-sm font-medium text-slate-900 mb-1 truncate">
                        <?= esc($log['full_name'] ?? 'Unknown') ?>
                    </div>
                    <div class="text-xs text-slate-500 mb-1.5 sm:mb-2 truncate">
                        <?= esc($log['email'] ?? '') ?>
                    </div>
                    <div class="text-xs text-slate-500">
                        <?= date('d/m/Y', strtotime($log['created_at'])) ?>
                        <span class="ml-1"><?= date('H:i:s', strtotime($log['created_at'])) ?></span>
                    </div>
                </div>
            </div>
            <div class="mt-2">
                <span class="inline-flex items-center px-2.5 sm:px-3 py-1 rounded-full text-xs font-medium break-words 
                    <?php
                    $action = strtolower($log['action']);
                    if (strpos($action, 'login') !== false) {
                        echo 'bg-blue-100 text-blue-700';
                    } elseif (strpos($action, 'logout') !== false) {
                        echo 'bg-slate-100 text-slate-700';
                    } elseif (strpos($action, 'menambah') !== false || strpos($action, 'create') !== false) {
                        echo 'bg-emerald-100 text-emerald-700';
                    } elseif (strpos($action, 'mengupdate') !== false || strpos($action, 'update') !== false) {
                        echo 'bg-amber-100 text-amber-700';
                    } elseif (strpos($action, 'menghapus') !== false || strpos($action, 'delete') !== false) {
                        echo 'bg-red-100 text-red-700';
                    } elseif (strpos($action, 'pesanan') !== false || strpos($action, 'order') !== false) {
                        echo 'bg-violet-100 text-violet-700';
                    } else {
                        echo 'bg-slate-100 text-slate-700';
                    }
                    ?>">
                    <span class="break-words"><?= esc($log['action']) ?></span>
                </span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Pagination -->
    <?php if (isset($totalPages) && $totalPages > 1): ?>
    <div class="p-3 sm:p-4 lg:p-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-4">
        <div class="text-xs sm:text-sm text-slate-600 text-center sm:text-left w-full sm:w-auto">
            Halaman <span class="font-semibold"><?= $page ?></span> dari <span class="font-semibold"><?= $totalPages ?></span>
        </div>
        <div class="flex gap-2 w-full sm:w-auto">
            <?php if ($page > 1): ?>
            <?php
            $prevParams = ['page' => $page - 1];
            if ($search) $prevParams['search'] = $search;
            if ($action) $prevParams['action'] = $action;
            if ($startDate) $prevParams['start_date'] = $startDate;
            if ($endDate) $prevParams['end_date'] = $endDate;
            if ($perPage) $prevParams['per_page'] = $perPage;
            $prevUrl = '?' . http_build_query($prevParams);
            ?>
            <a href="<?= $prevUrl ?>" 
               class="flex-1 sm:flex-none px-3 sm:px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors text-center text-xs sm:text-sm">
                <span class="hidden sm:inline">Sebelumnya</span>
                <span class="sm:hidden">← Prev</span>
            </a>
            <?php endif; ?>
            
            <?php if ($page < $totalPages): ?>
            <?php
            $nextParams = ['page' => $page + 1];
            if ($search) $nextParams['search'] = $search;
            if ($action) $nextParams['action'] = $action;
            if ($startDate) $nextParams['start_date'] = $startDate;
            if ($endDate) $nextParams['end_date'] = $endDate;
            if ($perPage) $nextParams['per_page'] = $perPage;
            $nextUrl = '?' . http_build_query($nextParams);
            ?>
            <a href="<?= $nextUrl ?>" 
               class="flex-1 sm:flex-none px-3 sm:px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg transition-colors text-center text-xs sm:text-sm">
                <span class="hidden sm:inline">Selanjutnya</span>
                <span class="sm:hidden">Next →</span>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

<script>
function changePerPage(value) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', value);
    url.searchParams.set('page', '1'); // Reset to first page when changing per page
    window.location.href = url.toString();
}
</script>
<?= $this->endSection() ?>

