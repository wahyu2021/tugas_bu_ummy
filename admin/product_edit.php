<?php
require_once __DIR__ . '/auth.php';

$id = (int)($_GET['id'] ?? 0);
$product = $id ? get_product($id) : null;
$categories = get_categories();
$errors = [];

// Definisikan path folder upload
define('UPLOAD_DIR', __DIR__ . '/../uploads/');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ensure_csrf();
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $catId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $imagePath = $product['main_image'] ?? null; // Simpan path gambar lama

    // Validasi dasar
    if (empty($name)) $errors[] = 'Nama produk wajib diisi.';
    if ($price <= 0) $errors[] = 'Harga harus lebih dari 0.';

    // Logika Upload Gambar
    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['main_image'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($file['type'], $allowedTypes)) {
            // Buat nama file yang unik untuk menghindari penimpaan
            $fileName = uniqid() . '-' . basename($file['name']);
            $targetPath = UPLOAD_DIR . $fileName;

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                // Hapus gambar lama jika ada & berhasil upload yang baru
                if ($imagePath && file_exists(UPLOAD_DIR . $imagePath)) {
                    unlink(UPLOAD_DIR . $imagePath);
                }
                $imagePath = $fileName; // Gunakan nama file baru
            } else {
                $errors[] = 'Gagal memindahkan file yang diunggah.';
            }
        } else {
            $errors[] = 'Tipe file gambar tidak valid. Hanya JPG, PNG, GIF yang diizinkan.';
        }
    }

    // Lanjutkan jika tidak ada error
    if (empty($errors)) {
        if ($id) {
            update_product($id, $name, $desc, $price, $stock, $catId, $imagePath);
        } else {
            create_product($name, $desc, $price, $stock, $catId, $imagePath);
        }
        header('Location: products.php');
        exit;
    }
}

include __DIR__ . '/partials/header.php';
?>

<h1 class="text-3xl font-bold mb-8"><?php echo $id ? 'Edit Produk' : 'Tambah Produk Baru'; ?></h1>

<?php if (!empty($errors)): ?>
  <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400">
    <?php foreach($errors as $err) echo "<p>" . esc($err) . "</p>"; ?>
  </div>
<?php endif; ?>

<div class="bg-surface p-6 rounded-xl border border-slate-700/50 max-w-2xl">
    <form method="post" enctype="multipart/form-data" class="space-y-4">
    <?php echo csrf_input(); ?>
    <div>
        <label for="name" class="block text-sm font-medium mb-1 text-gray-300">Nama Produk</label>
        <input id="name" name="name" type="text" value="<?php echo esc($product['name'] ?? ''); ?>" required class="w-full px-3 py-2 rounded-lg bg-slate-950/50 border border-slate-700 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
    </div>
    <div>
        <label for="description" class="block text-sm font-medium mb-1 text-gray-300">Deskripsi</label>
        <textarea id="description" name="description" rows="4" class="w-full px-3 py-2 rounded-lg bg-slate-950/50 border border-slate-700 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"><?php echo esc($product['description'] ?? ''); ?></textarea>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
        <label for="price" class="block text-sm font-medium mb-1 text-gray-300">Harga</label>
        <input id="price" name="price" type="number" step="1" value="<?php echo esc($product['price'] ?? ''); ?>" required class="w-full px-3 py-2 rounded-lg bg-slate-950/50 border border-slate-700 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
        </div>
        <div>
        <label for="stock" class="block text-sm font-medium mb-1 text-gray-300">Stok</label>
        <input id="stock" name="stock" type="number" value="<?php echo esc($product['stock'] ?? '0'); ?>" required class="w-full px-3 py-2 rounded-lg bg-slate-950/50 border border-slate-700 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
        </div>
    </div>
    <div>
        <label for="category_id" class="block text-sm font-medium mb-1 text-gray-300">Kategori</label>
        <select id="category_id" name="category_id" class="w-full p-2.5 rounded-lg bg-slate-950/50 border border-slate-700 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
        <option value="">-- Pilih Kategori --</option>
        <?php foreach($categories as $cat): ?>
            <option value="<?php echo (int)$cat['id']; ?>" <?php echo (($product['category_id'] ?? '') == $cat['id']) ? 'selected' : ''; ?>>
            <?php echo esc($cat['name']); ?>
            </option>
        <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1 text-gray-300">Gambar Utama</label>
        <?php if ($product && $product['main_image']): ?>
            <div class="my-2">
                <img src="../uploads/<?php echo esc($product['main_image']); ?>" alt="Gambar saat ini" class="h-24 rounded-lg border border-slate-700">
                <p class="text-xs text-gray-400 mt-1">Gambar saat ini. Upload file baru untuk menggantinya.</p>
            </div>
        <?php endif; ?>
        <input name="main_image" type="file" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-slate-900 hover:file:bg-primary/90 file:cursor-pointer">
    </div>
    <div class="pt-4 flex gap-2">
        <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary text-slate-900 font-bold hover:brightness-105 transition">Simpan Produk</button>
        <a href="products.php" class="px-5 py-2.5 rounded-lg bg-slate-700 hover:bg-slate-600 transition font-bold">Batal</a>
    </div>
    </form>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>