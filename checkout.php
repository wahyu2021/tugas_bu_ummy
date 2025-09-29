<?php
require_once __DIR__ . '/init.php';
require_login();
ensure_csrf();

$feedback = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  [$ok, $res] = place_order((int)current_user()['id']);
  if ($ok) {
    header('Location: checkout.php?success=1&order_id='.(int)$res);
    exit;
  } else {
    $feedback = $res;
  }
}

$cart = cart_items();
include __DIR__ . '/partials/header.php';
?>
<h1>Checkout</h1>
<?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
  <div class="card" style="padding:.9rem">
    <h3>Terima kasih!</h3>
    <p>Pesanan Anda telah dibuat dengan nomor order #<?php echo (int)($_GET['order_id'] ?? 0); ?>.</p>
    <p><a class="btn primary" href="products.php">Belanja lagi</a></p>
  </div>
<?php else: ?>
  <?php if (!empty($feedback)): ?>
    <div class="card" style="padding:.9rem;border-color:#ef4444">
      <p><?php echo esc($feedback); ?></p>
    </div>
  <?php endif; ?>

  <?php if (empty($cart['items'])): ?>
    <p>Keranjang kosong.</p>
    <p><a class="btn primary" href="products.php">Cari produk</a></p>
  <?php else: ?>
    <div class="card" style="padding:.9rem;margin-bottom:.8rem">
      <h3>Ringkasan</h3>
      <ul style="list-style:none;padding:0;margin:.5rem 0;display:grid;gap:.35rem">
        <?php foreach ($cart['items'] as $it): ?>
          <li style="display:flex;justify-content:space-between">
            <span><?php echo esc($it['name']); ?> × <?php echo (int)$it['qty']; ?></span>
            <span>Rp <?php echo number_format($it['line_total'],0,',','.'); ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
      <div style="display:flex;justify-content:space-between;font-weight:700">
        <span>Total</span>
        <span>Rp <?php echo number_format($cart['subtotal'],0,',','.'); ?></span>
      </div>
    </div>
    <form method="post">
      <?php echo csrf_input(); ?>
      <button class="btn primary" type="submit">Buat Pesanan</button>
    </form>
  <?php endif; ?>
<?php endif; ?>
<?php include __DIR__ . '/partials/footer.php'; ?>
