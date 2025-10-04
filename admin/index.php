<?php
require_once __DIR__ . '/auth.php';
include __DIR__ . '/partials/header.php';

$totalProducts = (int)db()->query('SELECT COUNT(*) FROM products')->fetchColumn();
$totalCategories = (int)db()->query('SELECT COUNT(*) FROM categories')->fetchColumn();
$totalOrders = (int)db()->query('SELECT COUNT(*) FROM orders')->fetchColumn();
?>

<div class="mb-8">
  <h1 class="text-3xl font-bold">Dasbor</h1>
  <p class="text-gray-400 mt-1">Selamat Datang kembali, <?php echo esc(current_user()['name']); ?>!</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
  <div class="bg-surface p-6 rounded-xl border border-slate-800">
    <div class="flex justify-between items-start">
        <div>
            <h2 class="text-lg font-semibold text-gray-400">Total Produk</h2>
            <p class="text-4xl font-bold mt-2 text-primary"><?php echo $totalProducts; ?></p>
        </div>
        <div class="p-2 bg-slate-800 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
        </div>
    </div>
  </div>
  <div class="bg-surface p-6 rounded-xl border border-slate-800">
    <div class="flex justify-between items-start">
        <div>
            <h2 class="text-lg font-semibold text-gray-400">Total Kategori</h2>
            <p class="text-4xl font-bold mt-2 text-accent"><?php echo $totalCategories; ?></p>
        </div>
        <div class="p-2 bg-slate-800 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
        </div>
    </div>
  </div>
  <div class="bg-surface p-6 rounded-xl border border-slate-800">
    <div class="flex justify-between items-start">
        <div>
            <h2 class="text-lg font-semibold text-gray-400">Total Pesanan</h2>
            <p class="text-4xl font-bold mt-2 text-yellow-400"><?php echo $totalOrders; ?></p>
        </div>
        <div class="p-2 bg-slate-800 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
        </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>