<?php
require_once __DIR__ . '/auth.php';
$orders = get_all_orders();
include __DIR__ . '/partials/header.php';
?>
<h1 class="text-3xl font-bold mb-6">Manajemen Pesanan</h1>
<div class="bg-surface border border-slate-700/50 rounded-xl overflow-x-auto">
  <table class="w-full text-left min-w-[640px]">
    <thead>
      <tr>
        <th class="p-4 font-semibold">ID Pesanan</th>
        <th class="p-4 font-semibold">Pelanggan</th>
        <th class="p-4 font-semibold">Total</th>
        <th class="p-4 font-semibold">Status</th>
        <th class="p-4 font-semibold">Tanggal</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($orders as $order): ?>
        <tr class="border-t border-slate-700/50">
          <td class="p-4">#<?php echo (int)$order['id']; ?></td>
          <td class="p-4"><?php echo esc($order['user_name']); ?><br><span class="text-sm text-gray-400"><?php echo esc($order['user_email']); ?></span></td>
          <td class="p-4 font-semibold">Rp <?php echo money($order['total_amount']); ?></td>
          <td class="p-4"><span class="px-2 py-1 text-xs rounded-full bg-yellow-500/20 text-yellow-300"><?php echo esc($order['status']); ?></span></td>
          <td class="p-4 text-gray-400"><?php echo date('d M Y, H:i', strtotime($order['created_at'])); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>