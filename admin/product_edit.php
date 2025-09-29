<?php
require_once __DIR__ . '/auth.php';

$id = (int)($_GET['id'] ?? 0);
$product = $id ? get_product($id) : null;
$categories = get_categories();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ensure_csrf();
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $catId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $image = trim($_POST['main_image'] ?? '');

    if (empty($name)) $errors[] = 'Nama produk wajib diisi.';
    if ($price <= 0) $errors[] = 'Harga harus lebih dari 0.';

    if (empty($errors)) {
        if ($id) {
            update_product($id, $name, $desc, $price, $stock, $catId, $image);
        } else {
            create_product($name, $desc, $price, $stock, $catId, $image);
        }
        header('Location: products.php');
        exit;
    }
}

include __DIR__ . '/partials/header.php';
?>

<h1 class="text-3xl font-bold mb-6"><?php echo $id ? 'Edit Produk' : 'Tambah Produk Baru'; ?></h1>

<?php if (!empty($errors)): ?>
  <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400">
    <?php foreach($errors as $err) echo "<p>$err</p>"; ?>
  </div>
<?php endif; ?>

<form method="post" class="bg-surface p-6 rounded-xl border border-slate-700/50 w-2xl space-y-4">
  <?php echo csrf_input(); ?>
  <div>
    <label class="block text-sm font-medium mb-1 text-gray-300">Nama Produk</label>
    <input name="name" type="text" value="<?php echo esc($product['name'] ?? ''); ?>" required class="w-full px-3 py-2 rounded-lg bg-slate-950/50 border border-slate-700 focus:ring-primary outline-none">
  </div>
  <div>
    <label class="block text-sm font-medium mb-1 text-gray-300">Deskripsi</label>
    <textarea name="description" rows="4" class="w-full px-3 py-2 rounded-lg bg-slate-950/50 border border-slate-700 focus:ring-primary outline-none"><?php echo esc($product['description'] ?? ''); ?></textarea>
  </div>
  <div class="grid grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium mb-1 text-gray-300">Harga</label>
      <input name="price" type="number" step="0.01" value="<?php echo esc($product['price'] ?? ''); ?>" required class="w-full px-3 py-2 rounded-lg bg-slate-950/50 border border-slate-700 focus:ring-primary outline-none">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1 text-gray-300">Stok</label>
      <input name="stock" type="number" value="<?php echo esc($product['stock'] ?? '0'); ?>" required class="w-full px-3 py-2 rounded-lg bg-slate-950/50 border border-slate-700 focus:ring-primary outline-none">
    </div>
  </div>
  <div>
    <label class="block text-sm font-medium mb-1 text-gray-300">Kategori</label>
    <select name="category_id" class="w-full p-2.5 rounded-lg bg-slate-950/50 border border-slate-700 focus:ring-primary outline-none">
      <option value="">-- Pilih Kategori --</option>
      <?php foreach($categories as $cat): ?>
        <option value="<?php echo (int)$cat['id']; ?>" <?php echo (($product['category_id'] ?? '') == $cat['id']) ? 'selected' : ''; ?>>
          <?php echo esc($cat['name']); ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div>
    <label class="block text-sm font-medium mb-1 text-gray-300">URL Gambar Utama</label>
    <input name="main_image" type="text" placeholder="https://..." value="<?php echo esc($product['main_image'] ?? ''); ?>" class="w-full px-3 py-2 rounded-lg bg-slate-950/50 border border-slate-700 focus:ring-primary outline-none">
  </div>
  <div class="pt-2">
    <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary text-slate-900 font-bold hover:brightness-105 transition">Simpan Produk</button>
    <a href="products.php" class="ml-2 px-5 py-2.5 rounded-lg bg-slate-700 hover:bg-slate-600 transition">Batal</a>
  </div>
</form>

<?php include __DIR__ . '/partials/footer.php'; ?>