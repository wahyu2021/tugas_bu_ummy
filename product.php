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
<article class="product-hero">
  <div>
    <img alt="<?php echo esc($product['name']); ?>" src="<?php echo esc(product_image_url($product['main_image'])); ?>">
    <?php if ($images): ?>
      <div class="gallery" style="margin-top:.6rem">
        <?php foreach ($images as $img): ?>
          <img alt="Gambar <?php echo esc($product['name']); ?>" src="<?php echo esc(product_image_url($img['image_path'])); ?>" height="72">
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
  <div class="stack">
    <h1><?php echo esc($product['name']); ?></h1>
    <p style="color:var(--color-muted)"><?php echo nl2br(esc($product['description'] ?? '')); ?></p>
    <p class="price" style="font-size:1.25rem">Rp <?php echo number_format($product['price'],0,',','.'); ?></p>
    <p>Stok: <?php echo (int)$product['stock']; ?></p>
    <form method="post" style="display:flex;gap:.5rem;align-items:center">
      <?php echo csrf_input(); ?>
      <div style="display:flex;align-items:center;gap:.25rem">
        <button type="button" class="btn subtle" data-dec>-</button>
        <input name="qty" type="number" min="1" max="<?php echo (int)$product['stock']; ?>" step="1" value="1" style="width:72px;text-align:center">
        <button type="button" class="btn subtle" data-inc>+</button>
      </div>
      <button type="submit" class="btn primary">Tambah ke Keranjang</button>
    </form>
  </div>
</article>
<?php include __DIR__ . '/partials/footer.php'; ?>
