<?php
require_once __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    ensure_csrf();
    delete_product((int)$_POST['id']);
    header('Location: products.php');
    exit;
}

$list = get_products(null, null, 100, 0);
include __DIR__ . '/partials/header.php';
?>

<div class="flex justify-between items-center mb-8">
  <h1 class="text-3xl font-bold">Manajemen Produk</h1>
  <a href="product_edit.php" class="px-5 py-2.5 rounded-lg bg-primary text-slate-900 font-bold hover:brightness-110 transition">Tambah Produk</a>
</div>

<div class="bg-surface border border-slate-800 rounded-xl overflow-x-auto">
  <table class="w-full text-left">
    <thead class="border-b border-slate-800">
      <tr>
        <th class="p-4 font-semibold text-sm text-gray-400">Nama</th>
        <th class="p-4 font-semibold text-sm text-gray-400">Kategori</th>
        <th class="p-4 font-semibold text-sm text-gray-400">Harga</th>
        <th class="p-4 font-semibold text-sm text-gray-400">Stok</th>
        <th class="p-4 font-semibold text-sm text-gray-400"></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($list as $p): ?>
        <tr class="border-b border-slate-800 last:border-b-0 hover:bg-slate-800/20 transition">
          <td class="p-4 font-medium"><?php echo esc($p['name']); ?></td>
          <td class="p-4 text-gray-400"><?php echo esc($p['category_name'] ?? 'N/A'); ?></td>
          <td class="p-4">Rp <?php echo money($p['price']); ?></td>
          <td class="p-4"><?php echo (int)$p['stock']; ?></td>
          <td class="p-4 flex justify-end gap-2">
            <a href="product_edit.php?id=<?php echo (int)$p['id']; ?>" class="px-3 py-1.5 rounded-lg bg-slate-700 hover:bg-slate-600 text-sm font-medium">Edit</a>
            <form method="post" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
              <?php echo csrf_input(); ?>
              <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
              <button type="submit" name="delete" class="px-3 py-1.5 rounded-lg bg-red-600/20 text-red-400 hover:bg-red-600/40 text-sm font-medium">Hapus</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>