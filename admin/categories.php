<?php
require_once __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    ensure_csrf();
    delete_category((int)$_POST['id']);
    header('Location: categories.php');
    exit;
}

$list = get_categories();
include __DIR__ . '/partials/header.php';
?>

<div class="flex justify-between items-center mb-6">
  <h1 class="text-3xl font-bold">Manajemen Kategori</h1>
  <a href="category_edit.php" class="px-5 py-2.5 rounded-lg bg-primary text-slate-900 font-bold hover:brightness-105 transition">Tambah Kategori</a>
</div>

<div class="bg-surface border border-slate-700/50 rounded-xl overflow-x-auto">
  <table class="w-full text-left">
    <thead>
      <tr><th class="p-4 font-semibold">Nama</th><th class="p-4 font-semibold">Aksi</th></tr>
    </thead>
    <tbody>
      <?php foreach ($list as $c): ?>
        <tr class="border-t border-slate-700/50">
          <td class="p-4 font-semibold"><?php echo esc($c['name']); ?></td>
          <td class="p-4 flex gap-2">
            <a href="category_edit.php?id=<?php echo (int)$c['id']; ?>" class="px-3 py-1.5 rounded-lg bg-slate-700 hover:bg-slate-600 text-sm">Edit</a>
            <form method="post" onsubmit="return confirm('Yakin hapus?');">
              <?php echo csrf_input(); ?>
              <input type="hidden" name="id" value="<?php echo (int)$c['id']; ?>">
              <button type="submit" name="delete" class="px-3 py-1.5 rounded-lg bg-red-600/50 hover:bg-red-600/70 text-sm">Hapus</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>