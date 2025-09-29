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
<section style="display:grid;gap:1rem;grid-template-columns:1fr">
  <form method="get" class="card" style="padding:.8rem;display:grid;gap:.6rem">
    <div style="display:grid;gap:.4rem">
      <label for="category">Kategori</label>
      <select id="category" name="category" style="padding:.6rem;border-radius:10px;background:rgba(2,6,23,.6);color:var(--color-text);border:1px solid rgba(148,163,184,.2)">
        <option value="">Semua</option>
        <?php foreach ($cats as $c): ?>
          <option value="<?php echo (int)$c['id']; ?>" <?php echo ($categoryId===(int)$c['id'])?'selected':''; ?>>
            <?php echo esc($c['name']); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div style="display:grid;gap:.4rem">
      <label for="q">Pencarian</label>
      <input id="q" name="q" type="search" value="<?php echo esc($q); ?>" placeholder="Nama produk...">
    </div>
    <div>
      <button class="btn primary" type="submit">Terapkan</button>
    </div>
  </form>

  <div class="grid products">
    <?php foreach ($list as $p): ?>
      <article class="card">
        <img alt="<?php echo esc($p['name']); ?>" src="<?php echo esc(product_image_url($p['main_image'])); ?>">
        <div class="content">
          <h3><?php echo esc($p['name']); ?></h3>
          <p><?php echo esc($p['category_name'] ?? ''); ?></p>
          <p class="price">Rp <?php echo number_format($p['price'],0,',','.'); ?></p>
          <div style="margin-top:.5rem">
            <a class="btn primary" href="product.php?id=<?php echo (int)$p['id']; ?>">Detail</a>
          </div>
        </div>
      </article>
    <?php endforeach; ?>
  </div>

  <?php if ($pages > 1): ?>
    <div style="display:flex;gap:.4rem;justify-content:center;margin-top:.5rem">
      <?php for ($i=1; $i<=$pages; $i++): ?>
        <?php
          $query = $_GET;
          $query['page'] = $i;
          $href = 'products.php?'.http_build_query($query);
        ?>
        <a class="btn <?php echo $i===$page?'primary':'subtle'; ?>" href="<?php echo esc($href); ?>"><?php echo $i; ?></a>
      <?php endfor; ?>
    </div>
  <?php endif; ?>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>
