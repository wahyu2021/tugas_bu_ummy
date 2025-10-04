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

<div class="max-w-2xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold"><?php echo $id ? 'Edit Kategori' : 'Tambah Kategori Baru'; ?></h1>
        <p class="text-gray-400 mt-1">Isi detail kategori di bawah ini.</p>
    </div>

    <form method="post" class="bg-surface p-6 rounded-xl border border-slate-800 space-y-5">
    <?php echo csrf_input(); ?>
    <div>
        <label for="name" class="block text-sm font-medium mb-2 text-gray-300">Nama Kategori</label>
        <input id="name" name="name" type="text" value="<?php echo esc($cat['name'] ?? ''); ?>" required class="w-full px-4 py-2 rounded-lg bg-bg border border-slate-700 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
    </div>
    <div>
        <label for="description" class="block text-sm font-medium mb-2 text-gray-300">Deskripsi</label>
        <textarea id="description" name="description" rows="3" class="w-full px-4 py-2 rounded-lg bg-bg border border-slate-700 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"><?php echo esc($cat['description'] ?? ''); ?></textarea>
    </div>
    <div class="flex gap-3 pt-2">
        <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary text-slate-900 font-bold hover:brightness-110 transition">Simpan</button>
        <a href="categories.php" class="px-5 py-2.5 rounded-lg bg-slate-800/80 hover:bg-slate-800 transition">Batal</a>
    </div>
    </form>
</div>


<?php include __DIR__ . '/partials/footer.php'; ?>