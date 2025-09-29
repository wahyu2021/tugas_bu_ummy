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

<div class="max-w-2xl mx-auto">
  <h1 class="text-3xl font-bold text-center mb-8">Checkout</h1>

  <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
    <div class="bg-surface p-8 rounded-xl border border-slate-700/50 text-center">
      <h3 class="text-2xl font-bold text-accent">Terima Kasih!</h3>
      <p class="mt-2 text-gray-400">Pesanan Anda telah berhasil dibuat dengan nomor order #<?php echo (int)($_GET['order_id'] ?? 0); ?>.</p>
      <a class="inline-block mt-6 px-5 py-2.5 rounded-lg bg-primary text-slate-900 font-bold hover:brightness-105 transition" href="products.php">Belanja Lagi</a>
    </div>

  <?php else: ?>

    <?php if (!empty($feedback)): ?>
      <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400">
        <p><?php echo esc($feedback); ?></p>
      </div>
    <?php endif; ?>

    <?php if (empty($cart['items'])): ?>
      <div class="bg-surface p-8 rounded-xl border border-slate-700/50 text-center">
          <p class="text-gray-400">Keranjang Anda kosong. Tidak ada yang bisa di-checkout.</p>
          <a class="inline-block mt-4 px-5 py-2.5 rounded-lg bg-primary text-slate-900 font-bold hover:brightness-105 transition" href="products.php">Cari Produk</a>
      </div>

    <?php else: ?>
      <div class="bg-surface p-6 rounded-xl border border-slate-700/50 mb-6">
        <h3 class="text-xl font-bold mb-4">Ringkasan Pesanan</h3>
        <ul class="space-y-3 text-gray-300">
          <?php foreach ($cart['items'] as $it): ?>
            <li class="flex justify-between items-center text-sm">
              <span>
                <?php echo esc($it['name']); ?>
                <span class="text-gray-400 ml-1">× <?php echo (int)$it['qty']; ?></span>
              </span>
              <span class="font-medium">Rp <?php echo number_format($it['line_total'],0,',','.'); ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
        <div class="flex justify-between font-bold text-lg mt-4 pt-4 border-t border-slate-700">
          <span>Total</span>
          <span>Rp <?php echo number_format($cart['subtotal'],0,',','.'); ?></span>
        </div>
      </div>
      <form method="post">
        <?php echo csrf_input(); ?>
        <button class="w-full text-center px-6 py-3 rounded-lg bg-primary text-slate-900 font-bold hover:brightness-105 transition" type="submit">Buat Pesanan Sekarang</button>
      </form>
    <?php endif; ?>

  <?php endif; ?>

</div>

<?php include __DIR__ . '/partials/footer.php'; ?>