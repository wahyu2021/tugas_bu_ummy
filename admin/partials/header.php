<!doctype html>
<html lang="id" class="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dasbor</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { darkMode: 'class', theme: { extend: { colors: { primary: '#0ea5e9', accent: '#10b981', surface: '#0f172a', bg: '#0b1220' } } } }
  </script>
</head>
<body class="bg-bg text-gray-200 antialiased flex">
  <aside class="w-64 bg-surface p-6 h-screen sticky top-0 flex flex-col border-r border-slate-700/50">
    <h1 class="font-bold text-xl text-primary mb-8">Admin Dasbor</h1>
    <nav class="flex flex-col space-y-2">
      <a href="index.php" class="px-4 py-2 rounded-lg hover:bg-slate-700 transition">Dasbor</a>
      <a href="products.php" class="px-4 py-2 rounded-lg hover:bg-slate-700 transition">Produk</a>
      <a href="categories.php" class="px-4 py-2 rounded-lg hover:bg-slate-700 transition">Kategori</a>
      <a href="orders.php" class="px-4 py-2 rounded-lg hover:bg-slate-700 transition">Pesanan</a>
    </nav>
    <div class="mt-auto">
      <a href="../index.php" class="block text-center text-sm px-4 py-2 rounded-lg bg-slate-700/70 hover:bg-slate-700 transition mb-2">Lihat Situs</a>
      <a href="../logout.php" class="block text-center text-sm px-4 py-2 rounded-lg bg-red-600/50 hover:bg-red-600/70 transition">Logout</a>
    </div>
  </aside>
  <main class="flex-1 p-8">