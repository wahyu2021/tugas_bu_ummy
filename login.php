<?php
require_once __DIR__ . '/init.php';
ensure_csrf();

// Fungsi untuk memeriksa apakah URL bersifat lokal
function is_local_url(string $url): bool {
    $path = parse_url($url, PHP_URL_PATH);
    $host = parse_url($url, PHP_URL_HOST);
    return !$host || ($host === $_SERVER['HTTP_HOST']);
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  if (login($email, $password)) {
    $next = $_GET['next'] ?? 'index.php';
    if (!is_local_url($next)) {
        $next = 'index.php';
    }
    header('Location: ' . $next);
    exit;
  } else {
    $error = 'Email atau password salah';
  }
}
include __DIR__ . '/partials/header.php';
?>
<div class="max-w-md mx-auto">
  <h1 class="text-3xl font-bold text-center mb-6">Login</h1>
  <?php if ($error): ?>
    <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400"><?php echo esc($error); ?></div>
  <?php endif; ?>
  <form method="post" class="bg-surface p-6 rounded-xl border border-slate-700/50 space-y-4">
    <?php echo csrf_input(); ?>
    <div>
      <label class="block text-sm font-medium mb-1 text-gray-300">Email</label>
      <input name="email" type="email" required placeholder="you@example.com" class="w-full px-3 py-2 rounded-lg bg-slate-950/50 border border-slate-700 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1 text-gray-300">Password</label>
      <input name="password" type="password" required class="w-full px-3 py-2 rounded-lg bg-slate-950/50 border border-slate-700 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
    </div>
    <button class="w-full px-5 py-2.5 rounded-lg bg-primary text-slate-900 font-bold hover:brightness-105 transition" type="submit">Masuk</button>
    <p class="text-sm text-center text-gray-400 pt-2">Belum punya akun? <a href="register.php" class="text-primary hover:underline">Daftar</a></p>
  </form>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>