<!doctype html>
<html lang="id" class="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dasbor</title>
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
            bg: '#020617',      // slate-950 (lebih gelap untuk kontras)
          }
        }
      }
    }
  </script>
</head>
<body class="bg-bg text-gray-300 antialiased">
<div class="flex min-h-screen">
  <aside class="w-64 flex-shrink-0 bg-surface flex flex-col border-r border-slate-800">
    <div class="h-16 flex items-center px-6">
      <a href="index.php" class="font-extrabold text-xl tracking-tight">
        Admin<span class="text-primary">Panel</span>
      </a>
    </div>
    <nav class="flex-1 px-4 py-4 space-y-2">
      <a href="index.php" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-slate-800 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" /></svg>
        Dasbor
      </a>
      <a href="products.php" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-slate-800 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" /></svg>
        Produk
      </a>
      <a href="categories.php" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-slate-800 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
        Kategori
      </a>
      <a href="orders.php" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-slate-800 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" /><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" /></svg>
        Pesanan
      </a>
    </nav>
    <div class="px-4 py-4 mt-auto border-t border-slate-800 space-y-2">
        <a href="../index.php" target="_blank" class="block text-center text-sm px-4 py-2 rounded-lg bg-slate-800/80 hover:bg-slate-800 transition">Lihat Situs</a>
        <a href="../logout.php" class="block text-center text-sm px-4 py-2 rounded-lg bg-red-600/20 text-red-400 hover:bg-red-600/40 transition">Logout</a>
    </div>
  </aside>

  <main class="flex-1 p-6 lg:p-8">