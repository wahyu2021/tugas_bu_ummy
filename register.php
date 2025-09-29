<?php
require_once __DIR__ . '/init.php';
ensure_csrf();

$msg = null; $err = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $password2 = $_POST['password2'] ?? '';
  if (strlen($password) < 6) {
    $err = 'Password minimal harus 6 karakter';
  } elseif ($password !== $password2) {
    $err = 'Konfirmasi password tidak cocok';
  } else {
    [$ok, $res] = register_user($name, $email, $password);
    if ($ok) { $msg = $res; } else { $err = $res; }
  }
}
include __DIR__ . '/partials/header.php';
?>
<div class="max-w-md mx-auto">
  <h1 class="text-3xl font-bold text-center mb-6">Daftar Akun Baru</h1>
  <?php if ($msg): ?>
    <div class="mb-4 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400"><?php echo esc($msg); ?></div>
  <?php endif; ?>
  <?php if ($err): ?>
    <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400"><?php echo esc($err); ?></div>
  <?php endif; ?>

  <form method="post" class="bg-surface p-6 rounded-xl border border-slate-700/50 space-y-4">
    <?php echo csrf_input(); ?>
    <div>
      <label class="block text-sm font-medium mb-1 text-gray-300">Nama</label>
      <input name="name" type="text" required class="w-full px-3 py-2 rounded-lg bg-slate-950/50 border border-slate-700 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1 text-gray-300">Email</label>
      <input name="email" type="email" required class="w-full px-3 py-2 rounded-lg bg-slate-950/50 border border-slate-700 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1 text-gray-300">Password</label>
      <input name="password" type="password" required minlength="6" class="w-full px-3 py-2 rounded-lg bg-slate-950/50 border border-slate-700 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1 text-gray-300">Ulangi Password</label>
      <input name="password2" type="password" required minlength="6" class="w-full px-3 py-2 rounded-lg bg-slate-950/50 border border-slate-700 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
    </div>
    <button class="w-full px-5 py-2.5 rounded-lg bg-primary text-slate-900 font-bold hover:brightness-105 transition" type="submit">Daftar</button>
    <p class="text-sm text-center text-gray-400 pt-2">Sudah punya akun? <a href="login.php" class="text-primary hover:underline">Login</a></p>
  </form>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>