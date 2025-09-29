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
<h1 class="text-3xl font-bold mb-6">Keranjang Belanja</h1>

<?php if (empty($cart['items'])): ?>
  <div class="bg-surface p-8 rounded-xl border border-slate-700/50 text-center">
    <p class="text-gray-400">Keranjang Anda kosong.</p>
    <a class="inline-block mt-4 px-5 py-2.5 rounded-lg bg-primary text-slate-900 font-bold hover:brightness-105 transition" href="products.php">Belanja sekarang</a>
  </div>
<?php else: ?>
  <form method="post" class="space-y-6">
    <?php echo csrf_input(); ?>
    <div class="bg-surface border border-slate-700/50 rounded-xl overflow-x-auto">
      <table class="w-full text-left min-w-[640px]">
        <thead class="border-b border-slate-700/50">
          <tr>
            <th class="p-4 font-semibold">Produk</th>
            <th class="p-4 font-semibold">Harga</th>
            <th class="p-4 font-semibold">Kuantitas</th>
            <th class="p-4 font-semibold text-right">Subtotal</th>
            <th class="p-4 font-semibold"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cart['items'] as $it): ?>
            <tr class="border-b border-slate-700/50 last:border-b-0">
              <td class="p-4">
                <div class="flex items-center gap-4">
                  <img src="<?php echo esc(product_image_url($it['image'])); ?>" alt="<?php echo esc($it['name']); ?>" class="w-16 h-16 rounded-md object-cover">
                  <div>
                    <div class="font-semibold"><?php echo esc($it['name']); ?></div>
                    <div class="text-sm text-gray-400">Stok: <?php echo (int)$it['stock']; ?></div>
                  </div>
                </div>
              </td>
              <td class="p-4">Rp <?php echo number_format($it['price'],0,',','.'); ?></td>
              <td class="p-4">
                <div class="flex items-center gap-1">
                  <button type="button" class="px-2 py-1 rounded bg-slate-700 hover:bg-slate-600" data-dec>-</button>
                  <input name="qty[<?php echo (int)$it['id']; ?>]" type="number" min="0" max="<?php echo (int)$it['stock']; ?>" value="<?php echo (int)$it['qty']; ?>" class="w-16 text-center bg-slate-950/50 border border-slate-600 rounded-md">
                  <button type="button" class="px-2 py-1 rounded bg-slate-700 hover:bg-slate-600" data-inc>+</button>
                </div>
              </td>
              <td class="p-4 font-semibold text-right">Rp <?php echo number_format($it['line_total'],0,',','.'); ?></td>
              <td class="p-4 text-center">
                <button class="px-3 py-1.5 rounded-lg bg-red-600/20 text-red-400 hover:bg-red-600/40 text-sm" type="submit" name="action" value="remove" onclick="return confirm('Hapus item?');">
                  <input type="hidden" name="product_id" value="<?php echo (int)$it['id']; ?>">
                  Hapus
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="flex justify-between items-start flex-wrap gap-6">
      <div class="flex gap-4">
        <button class="px-4 py-2 rounded-lg bg-slate-700/70 border border-slate-600 hover:bg-slate-700 transition" type="submit" name="action" value="update">Perbarui Kuantitas</button>
        <button class="px-4 py-2 rounded-lg bg-slate-700/70 border border-slate-600 hover:bg-slate-700 transition" type="submit" name="action" value="clear" onclick="return confirm('Kosongkan keranjang?');">Kosongkan Keranjang</button>
      </div>
      <div class="text-right flex-grow md:flex-grow-0">
        <div class="text-gray-400">Total</div>
        <div class="text-2xl font-bold text-accent">Rp <?php echo number_format($cart['subtotal'],0,',','.'); ?></div>
        <div class="mt-4">
          <a class="block w-full text-center px-6 py-3 rounded-lg bg-primary text-slate-900 font-bold hover:brightness-105 transition" href="checkout.php">Lanjut ke Checkout</a>
        </div>
      </div>
    </div>
  </form>
<?php endif; ?>
<?php include __DIR__ . '/partials/footer.php'; ?>