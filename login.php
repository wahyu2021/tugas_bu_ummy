<?php
require_once __DIR__ . '/init.php';
ensure_csrf();

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  if (login($email, $password)) {
    $next = $_GET['next'] ?? 'index.php';
    header('Location: ' . $next);
    exit;
  } else {
    $error = 'Email atau password salah';
  }
}
include __DIR__ . '/partials/header.php';
?>
<h1>Login</h1>
<?php if ($error): ?>
  <div class="card" style="padding:.9rem;border-color:#ef4444"><?php echo esc($error); ?></div>
<?php endif; ?>
<form method="post" class="card" style="padding:.9rem;display:grid;gap:.6rem;max-width:420px">
  <?php echo csrf_input(); ?>
  <label>Email
    <input name="email" type="email" required placeholder="you@example.com">
  </label>
  <label>Password
    <input name="password" type="password" required>
  </label>
  <button class="btn primary" type="submit">Masuk</button>
  <p style="color:var(--color-muted);font-size:.9rem">Belum punya akun? <a href="register.php">Daftar</a></p>
</form>
<?php include __DIR__ . '/partials/footer.php'; ?>
