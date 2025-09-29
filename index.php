<?php
require_once __DIR__ . '/init.php';
$cats = get_categories();
$latest = get_products(null, null, 8, 0);
include __DIR__ . '/partials/header.php';
?>
<section class="p-6 rounded-xl border border-slate-300/10 bg-gradient-to-b from-primary/10 to-accent/10">
  <div>
    <h1 class="text-4xl font-bold text-balance">Belanja mudah, cepat, dan aman.</h1>
    <p class="mt-2 mb-6 text-gray-400">Produk terbaru, harga bersahabat. Tanpa ribet.</p>
    <div class="flex gap-3 flex-wrap">
      <a class="px-5 py-2.5 rounded-lg bg-primary text-slate-900 font-bold hover:brightness-105 transition" href="products.php">Jelajahi Produk</a>
      <a class="px-5 py-2.5 rounded-lg bg-slate-700/70 border border-slate-600 hover:bg-slate-700 transition" href="cart.php">Lihat Keranjang</a>
    </div>
  </div>
</section>

<section class="mt-10">
  <h2 class="text-2xl font-bold mb-4">Kategori</h2>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <?php foreach ($cats as $c): ?>
      <a class="block p-4 rounded-xl bg-surface border border-slate-700/50 hover:border-primary/50 transition text-decoration-none" href="products.php?category=<?php echo (int)$c['id']; ?>">
        <strong class="text-gray-200"><?php echo esc($c['name']); ?></strong>
        <span class="block text-sm text-gray-400 mt-1">Lihat produk</span>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="mt-10">
  <h2 class="text-2xl font-bold mb-4">Terbaru</h2>
  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    <?php foreach ($latest as $p): ?>
      <article class="bg-surface border border-slate-700/50 rounded-xl overflow-hidden flex flex-col">
        <img class="aspect-video object-cover" alt="<?php echo esc($p['name']); ?>" src="<?php echo esc(product_image_url($p['main_image'])); ?>">
        <div class="p-4 flex flex-col flex-grow">
          <h3 class="font-bold text-base leading-tight"><?php echo esc($p['name']); ?></h3>
          <p class="text-sm text-gray-400 mt-1"><?php echo esc($p['category_name'] ?? ''); ?></p>
          <p class="font-bold text-accent mt-2">Rp <?php echo number_format($p['price'],0,',','.'); ?></p>
          <div class="mt-auto pt-4">
            <a class="block text-center w-full px-4 py-2 rounded-lg bg-primary text-slate-900 font-bold hover:brightness-105 transition text-sm" href="product.php?id=<?php echo (int)$p['id']; ?>">Detail</a>
          </div>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>