<?php
require_once __DIR__ . '/init.php';
ensure_csrf();

$id = (int)($_GET['id'] ?? 0);
$product = get_product($id);
if (!$product) {
  http_response_code(404);
  exit('Produk tidak ditemukan');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $qty = max(1, (int)($_POST['qty'] ?? 1));
  cart_add($id, $qty);
  header('Location: cart.php');
  exit;
}

$images = get_product_images($id);
include __DIR__ . '/partials/header.php';
?>
<article class="grid md:grid-cols-2 gap-8">
  <div>
    <img class="rounded-lg border border-slate-700 w-full aspect-square object-cover" alt="<?php echo esc($product['name']); ?>" src="<?php echo esc(product_image_url($product['main_image'])); ?>">
    <?php if ($images): ?>
      <div class="flex gap-2 mt-3 overflow-x-auto pb-2">
        <?php foreach ($images as $img): ?>
          <img class="h-20 rounded-md border border-slate-700 cursor-pointer" alt="Gambar <?php echo esc($product['name']); ?>" src="<?php echo esc(product_image_url($img['image_path'])); ?>">
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
  <div class="space-y-4">
    <h1 class="text-3xl font-bold"><?php echo esc($product['name']); ?></h1>
    <p class="text-gray-400 leading-relaxed"><?php echo nl2br(esc($product['description'] ?? '')); ?></p>
    <p class="text-3xl font-bold text-accent">Rp <?php echo number_format($product['price'],0,',','.'); ?></p>
    <p class="text-sm text-gray-300">Stok: <?php echo (int)$product['stock']; ?></p>
    <form method="post" class="flex gap-4 items-center pt-4 border-t border-slate-700/50">
      <?php echo csrf_input(); ?>
      <div class="flex items-center gap-1">
        <button type="button" class="px-3 py-2 rounded-lg bg-slate-700/70 hover:bg-slate-700" data-dec>-</button>
        <input name="qty" type="number" min="1" max="<?php echo (int)$product['stock']; ?>" step="1" value="1" class="w-20 text-center bg-slate-950/50 border border-slate-700 rounded-lg focus:ring-primary outline-none">
        <button type="button" class="px-3 py-2 rounded-lg bg-slate-700/70 hover:bg-slate-700" data-inc>+</button>
      </div>
      <button type="submit" class="flex-1 px-5 py-2.5 rounded-lg bg-primary text-slate-900 font-bold hover:brightness-105 transition">Tambah ke Keranjang</button>
    </form>
  </div>
</article>
<?php include __DIR__ . '/partials/footer.php'; ?>