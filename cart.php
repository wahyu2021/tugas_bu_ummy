<?php
require_once __DIR__ . '/init.php';
ensure_csrf();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  if ($action === 'update') {
    foreach (($_POST['qty'] ?? []) as $pid => $q) {
      cart_update((int)$pid, (int)$q);
    }
  } elseif ($action === 'remove') {
    cart_remove((int)($_POST['product_id'] ?? 0));
  } elseif ($action === 'clear') {
    cart_clear();
  }
  header('Location: cart.php');
  exit;
}

$cart = cart_items();
include __DIR__ . '/partials/header.php';
?>
<h1>Keranjang</h1>

<?php if (empty($cart['items'])): ?>
  <p>Keranjang Anda kosong.</p>
  <p><a class="btn primary" href="products.php">Belanja sekarang</a></p>
<?php else: ?>
  <form method="post">
    <?php echo csrf_input(); ?>
    <input type="hidden" name="action" value="update">
    <table class="table">
      <thead>
        <tr>
          <th>Produk</th>
          <th>Harga</th>
          <th>Kuantitas</th>
          <th>Subtotal</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cart['items'] as $it): ?>
          <tr>
            <td style="display:flex;align-items:center;gap:.6rem">
              <img src="<?php echo esc(product_image_url($it['image'])); ?>" alt="<?php echo esc($it['name']); ?>" width="60" height="60" style="border-radius:8px">
              <div>
                <div><?php echo esc($it['name']); ?></div>
                <div style="color:var(--color-muted);font-size:.85rem">Stok: <?php echo (int)$it['stock']; ?></div>
              </div>
            </td>
            <td>Rp <?php echo number_format($it['price'],0,',','.'); ?></td>
            <td>
              <div style="display:flex;align-items:center;gap:.25rem">
                <button type="button" class="btn subtle" data-dec>-</button>
                <input name="qty[<?php echo (int)$it['id']; ?>]" type="number" min="0" max="<?php echo (int)$it['stock']; ?>" step="1" value="<?php echo (int)$it['qty']; ?>" style="width:72px;text-align:center">
                <button type="button" class="btn subtle" data-inc>+</button>
              </div>
            </td>
            <td>Rp <?php echo number_format($it['line_total'],0,',','.'); ?></td>
            <td>
              <form method="post" style="display:inline">
                <?php echo csrf_input(); ?>
                <input type="hidden" name="action" value="remove">
                <input type="hidden" name="product_id" value="<?php echo (int)$it['id']; ?>">
                <button class="btn subtle" data-remove>Hapus</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:.8rem">
      <div style="display:flex;gap:.5rem;flex-wrap:wrap">
        <button class="btn subtle" type="submit">Perbarui Kuantitas</button>
        <form method="post" style="display:inline">
          <?php echo csrf_input(); ?>
          <input type="hidden" name="action" value="clear">
          <button class="btn subtle" type="submit">Kosongkan Keranjang</button>
        </form>
      </div>
      <div style="text-align:right">
        <div style="color:var(--color-muted)">Total</div>
        <div class="price" style="font-size:1.25rem">Rp <?php echo number_format($cart['subtotal'],0,',','.'); ?></div>
        <div style="margin-top:.5rem">
          <a class="btn primary" href="checkout.php">Lanjut ke Checkout</a>
        </div>
      </div>
    </div>
  </form>
<?php endif; ?>
<?php include __DIR__ . '/partials/footer.php'; ?>
