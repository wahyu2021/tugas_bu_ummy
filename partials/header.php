<?php
$user = current_user();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Toko Sederhana</title>
  <link rel="stylesheet" href="public/style.css">
  <script defer src="public/app.js"></script>
</head>
<body>
<header class="site-header">
  <div class="container header-inner">
    <a class="brand" href="index.php">Toko<span>Sederhana</span></a>
    <form class="search" action="products.php" method="get">
      <input name="q" type="search" placeholder="Cari produk..." aria-label="Cari produk" value="<?php echo esc($_GET['q'] ?? ''); ?>">
      <button type="submit" class="btn primary">Cari</button>
    </form>
    <nav class="nav">
      <a href="products.php" class="nav-link">Produk</a>
      <a href="cart.php" class="nav-link">Keranjang</a>
      <?php if ($user): ?>
        <span class="nav-user">Halo, <?php echo esc($user['name']); ?></span>
        <a href="logout.php" class="btn subtle">Logout</a>
      <?php else: ?>
        <a href="login.php" class="btn subtle">Login</a>
        <a href="register.php" class="btn primary">Daftar</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container main">
