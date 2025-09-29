<?php
require_once __DIR__ . '/init.php';
$cats = get_categories();
$latest = get_products(null, null, 8, 0);
include __DIR__ . '/partials/header.php';
?>
<section class="hero">
  <div>
    <h1 class="text-balance">Belanja mudah, cepat, dan aman.</h1>
    <p>Produk terbaru, harga bersahabat. Tanpa ribet.</p>
    <div style="display:flex;gap:.5rem;flex-wrap:wrap">
      <a class="btn primary" href="products.php">Jelajahi Produk</a>
      <a class="btn subtle" href="cart.php">Lihat Keranjang</a>
    </div>
  </div>
</section>

<section style="margin-top:1.25rem">
  <h2 style="margin:.25rem 0 1rem">Kategori</h2>
  <div class="grid" style="grid-template-columns:repeat(2,1fr);gap:.6rem">
    <?php foreach ($cats as $c): ?>
      <a class="card" href="products.php?category=<?php echo (int)$c['id']; ?>" style="padding:.9rem;text-decoration:none">
        <strong style="color:var(--color-text)"><?php echo esc($c['name']); ?></strong>
        <span style="color:var(--color-muted);font-size:.9rem">Lihat produk</span>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section style="margin-top:1.25rem">
  <h2 style="margin:.25rem 0 1rem">Terbaru</h2>
  <div class="grid products">
    <?php foreach ($latest as $p): ?>
      <article class="card">
        <img alt="<?php echo esc($p['name']); ?>" src="<?php echo esc(product_image_url($p['main_image'])); ?>">
        <div class="content">
          <h3><?php echo esc($p['name']); ?></h3>
          <p><?php echo esc($p['category_name'] ?? ''); ?></p>
          <p class="price">Rp <?php echo number_format($p['price'],0,',','.'); ?></p>
          <div style="margin-top:.5rem;display:flex;gap:.5rem">
            <a class="btn primary" href="product.php?id=<?php echo (int)$p['id']; ?>">Detail</a>
          </div>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>
