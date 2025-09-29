<?php
require_once __DIR__ . '/auth.php';
include __DIR__ . '/partials/header.php';

$totalProducts = (int)db()->query('SELECT COUNT(*) FROM products')->fetchColumn();
$totalCategories = (int)db()->query('SELECT COUNT(*) FROM categories')->fetchColumn();
$totalOrders = (int)db()->query('SELECT COUNT(*) FROM orders')->fetchColumn();
?>

<h1 class="text-3xl font-bold mb-6">Selamat Datang, <?php echo esc(current_user()['name']); ?>!</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
  <div class="bg-surface p-6 rounded-xl border border-slate-700/50">
    <h2 class="text-lg font-semibold text-gray-400">Total Produk</h2>
    <p class="text-4xl font-bold mt-2"><?php echo $totalProducts; ?></p>
  </div>
  <div class="bg-surface p-6 rounded-xl border border-slate-700/50">
    <h2 class="text-lg font-semibold text-gray-400">Total Kategori</h2>
    <p class="text-4xl font-bold mt-2"><?php echo $totalCategories; ?></p>
  </div>
  <div class="bg-surface p-6 rounded-xl border border-slate-700/50">
    <h2 class="text-lg font-semibold text-gray-400">Total Pesanan</h2>
    <p class="text-4xl font-bold mt-2"><?php echo $totalOrders; ?></p>
  </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>