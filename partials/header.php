<?php
$user = current_user();
?>
<!doctype html>
<html lang="id" class="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Toko Sederhana</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            primary: '#0ea5e9', // sky-500
            accent: '#10b981',  // emerald-500
            surface: '#0f172a', // slate-900
            bg: '#0b1220',      // Custom darker background
          }
        }
      }
    }
  </script>
  <script defer src="public/app.js"></script>
  <style>
    /* Sedikit custom style untuk body background */
    body {
      background-color: theme('colors.bg');
    }
  </style>
</head>
<body class="bg-bg text-gray-200 antialiased">
<header class="sticky top-0 z-10 bg-surface/80 backdrop-blur-sm border-b border-slate-300/10">
  <div class="container mx-auto px-4">
    <div class="flex items-center justify-between gap-4 py-3">
      <a class="font-extrabold text-lg tracking-wide" href="index.php">
        Toko<span class="text-primary">Sederhana</span>
      </a>
      <form class="flex-1 max-w-xl mx-4 hidden md:flex" action="products.php" method="get">
        <div class="flex gap-2 w-full">
          <input name="q" type="search" placeholder="Cari produk..." aria-label="Cari produk" value="<?php echo esc($_GET['q'] ?? ''); ?>" class="w-full px-4 py-2 rounded-lg bg-slate-950/50 border border-slate-700 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
          <button type="submit" class="px-5 py-2 rounded-lg bg-primary text-slate-900 font-bold hover:brightness-105 transition">Cari</button>
        </div>
      </form>
      <nav class="flex items-center gap-2">
        <a href="products.php" class="px-3 py-1.5 rounded-md hover:bg-slate-700/50 transition text-gray-300">Produk</a>
        <a href="cart.php" class="px-3 py-1.5 rounded-md hover:bg-slate-700/50 transition text-gray-300">Keranjang</a>
        <?php if ($user): ?>
          <span class="text-gray-400 text-sm hidden md:block ml-2">Halo, <?php echo esc($user['name']); ?></span>
          <a href="logout.php" class="px-4 py-2 rounded-lg bg-slate-700/70 border border-slate-600 hover:bg-slate-700 transition text-sm">Logout</a>
        <?php else: ?>
          <a href="login.php" class="px-4 py-2 rounded-lg bg-slate-700/70 border border-slate-600 hover:bg-slate-700 transition text-sm">Login</a>
          <a href="register.php" class="px-4 py-2 rounded-lg bg-primary text-slate-900 font-bold hover:brightness-105 transition text-sm">Daftar</a>
        <?php endif; ?>
      </nav>
    </div>
  </div>
</header>
<main class="container mx-auto px-4 py-6 md:py-10">