<?php
require_once __DIR__ . '/auth.php';
$id = (int)($_GET['id'] ?? 0);
$cat = $id ? get_category($id) : null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ensure_csrf();
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    if (!empty($name)) {
        if ($id) {
            update_category($id, $name, $desc);
        } else {
            create_category($name, $desc);
        }
        header('Location: categories.php');
        exit;
    }
}
include __DIR__ . '/partials/header.php';
?>
<h1 class="text-3xl font-bold mb-6"><?php echo $id ? 'Edit Kategori' : 'Tambah Kategori'; ?></h1>
<form method="post" class="bg-surface p-6 rounded-xl border border-slate-700/50 max-w-lg space-y-4">
  <?php echo csrf_input(); ?>
  <div>
    <label class="block text-sm font-medium mb-1">Nama Kategori</label>
    <input name="name" type="text" value="<?php echo esc($cat['name'] ?? ''); ?>" required class="w-full px-3 py-2 rounded-lg bg-slate-950/50 border border-slate-700">
  </div>
  <div>
    <label class="block text-sm font-medium mb-1">Deskripsi</label>
    <textarea name="description" rows="3" class="w-full px-3 py-2 rounded-lg bg-slate-950/50 border border-slate-700"><?php echo esc($cat['description'] ?? ''); ?></textarea>
  </div>
  <div>
    <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary text-slate-900 font-bold">Simpan</button>
    <a href="categories.php" class="ml-2">Batal</a>
  </div>
</form>
<?php include __DIR__ . '/partials/footer.php'; ?>