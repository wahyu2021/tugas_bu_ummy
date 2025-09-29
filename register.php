<?php
require_once __DIR__ . '/init.php';
ensure_csrf();

$msg = null; $err = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $password2 = $_POST['password2'] ?? '';
  if ($password !== $password2) {
    $err = 'Konfirmasi password tidak cocok';
  } else {
    [$ok, $res] = register_user($name, $email, $password);
    if ($ok) { $msg = $res; } else { $err = $res; }
  }
}
include __DIR__ . '/partials/header.php';
?>
<h1>Daftar</h1>
<?php if ($msg): ?>
  <div class="card" style="padding:.9rem;border-color:rgba(16,185,129,.6)"><?php echo esc($msg); ?></div>
<?php endif; ?>
<?php if ($err): ?>
  <div class="card" style="padding:.9rem;border-color:#ef4444"><?php echo esc($err); ?></div>
<?php endif; ?>
<form method="post" class="card" style="padding:.9rem;display:grid;gap:.6rem;max-width:480px">
  <?php echo csrf_input(); ?>
  <label>Nama
    <input name="name" type="text" required>
  </label>
  <label>Email
    <input name="email" type="email" required>
  </label>
  <label>Password
    <input name="password" type="password" required minlength="6">
  </label>
  <label>Ulangi Password
    <input name="password2" type="password" required minlength="6">
  </label>
  <button class="btn primary" type="submit">Daftar</button>
  <p style="color:var(--color-muted);font-size:.9rem">Sudah punya akun? <a href="login.php">Login</a></p>
</form>
<?php include __DIR__ . '/partials/footer.php'; ?>
