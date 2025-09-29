<?php
require_once __DIR__ . '/init.php';
$cats = get_categories();
$categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
$q = isset($_GET['q']) ? trim($_GET['q']) : null;

$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 12;
$total = count_products($categoryId, $q);
$offset = ($page - 1) * $perPage;
$list = get_products($categoryId, $q, $perPage, $offset);
$pages = max(1, (int)ceil($total / $perPage));

include __DIR__ . '/partials/header.php';
?>
<section class="grid md:grid-cols-4 gap-6">
  <aside>
    <form method="get" class="bg-surface p-4 rounded-xl border border-slate-700/50 space-y-4 sticky top-24">
      <div>
        <label for="category" class="block text-sm font-medium text-gray-300 mb-1">Kategori</label>
        <select id="category" name="category" class="w-full p-2.5 rounded-lg bg-slate-950/50 border border-slate-700 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
          <option value="">Semua</option>
          <?php foreach ($cats as $c): ?>
            <option value="<?php echo (int)$c['id']; ?>" <?php echo ($categoryId===(int)$c['id'])?'selected':''; ?>>
              <?php echo esc($c['name']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label for="q" class="block text-sm font-medium text-gray-300 mb-1">Pencarian</label>
        <input id="q" name="q" type="search" value="<?php echo esc($q); ?>" placeholder="Nama produk..." class="w-full px-3 py-2 rounded-lg bg-slate-950/50 border border-slate-700 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
      </div>
      <div>
        <button class="w-full px-5 py-2.5 rounded-lg bg-primary text-slate-900 font-bold hover:brightness-105 transition" type="submit">Terapkan Filter</button>
      </div>
    </form>
  </aside>

  <div class="md:col-span-3">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <?php foreach ($list as $p): ?>
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

    <?php if ($pages > 1): ?>
      <div class="flex gap-2 justify-center mt-8">
        <?php for ($i=1; $i<=$pages; $i++): ?>
          <?php
            $query = $_GET;
            $query['page'] = $i;
            $href = 'products.php?'.http_build_query($query);
            $activeClass = $i === $page ? 'bg-primary text-slate-900 font-bold' : 'bg-surface hover:bg-slate-700';
          ?>
          <a class="px-4 py-2 rounded-lg transition <?php echo $activeClass; ?>" href="<?php echo esc($href); ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>