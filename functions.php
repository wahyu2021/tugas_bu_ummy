<?php

function esc(?string $s): string {
  return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

// CSRF
function csrf_token(): string {
  return $_SESSION['csrf_token'] ?? '';
}
function csrf_input(): string {
  return '<input type="hidden" name="csrf_token" value="'.esc(csrf_token()).'">';
}
function ensure_csrf(): void {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
      http_response_code(400);
      exit('CSRF token invalid.');
    }
  }
}

// Auth
function current_user(): ?array {
  return $_SESSION['user'] ?? null;
}
function require_login(): void {
  if (!current_user()) {
    $next = $_SERVER['REQUEST_URI'] ?? 'index.php';
    header('Location: login.php?next=' . urlencode($next));
    exit;
  }
}
function find_user_by_email(string $email): ?array {
  $stmt = db()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
  $stmt->execute([$email]);
  $row = $stmt->fetch();
  return $row ?: null;
}
function login(string $email, string $password): bool {
  $user = find_user_by_email($email);
  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = [
      'id' => (int)$user['id'],
      'name' => $user['name'],
      'email' => $user['email'],
      'is_admin' => (bool)$user['is_admin'], // Tambahkan ini
    ];
    return true;
  }
  return false;
}

function register_user(string $name, string $email, string $password): array {
  if (find_user_by_email($email)) {
    return [false, 'Email sudah terdaftar'];
  }
  $hash = password_hash($password, PASSWORD_DEFAULT);
  $stmt = db()->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
  $stmt->execute([$name, $email, $hash]);
  return [true, 'Registrasi berhasil, silakan login'];
}
function logout(): void {
  unset($_SESSION['user']);
}

// Helper login state and format rupiah
function is_logged_in(): bool {
  return current_user() !== null;
}

function current_user_id(): ?int {
  $u = current_user();
  return $u ? (int)$u['id'] : null;
}

function money($x): string {
  return number_format((float)$x, 0, ',', '.');
}

// Kategori & Produk
function get_categories(): array {
  $stmt = db()->query('SELECT id, name FROM categories ORDER BY name');
  return $stmt->fetchAll();
}
function count_products(?int $categoryId, ?string $q): int {
  $sql = 'SELECT COUNT(*) as c FROM products WHERE 1=1';
  $params = [];
  if ($categoryId) { $sql .= ' AND category_id = ?'; $params[] = $categoryId; }
  if ($q) { $sql .= ' AND name LIKE ?'; $params[] = "%$q%"; }
  $stmt = db()->prepare($sql);
  $stmt->execute($params);
  return (int)($stmt->fetch()['c'] ?? 0);
}
function get_products(?int $categoryId, ?string $q, int $limit = 12, int $offset = 0): array {
  $sql = 'SELECT p.*, c.name AS category_name
          FROM products p
          LEFT JOIN categories c ON p.category_id = c.id
          WHERE 1=1';
  $params = [];
  if ($categoryId) { $sql .= ' AND p.category_id = ?'; $params[] = $categoryId; }
  if ($q) { $sql .= ' AND p.name LIKE ?'; $params[] = "%$q%"; }
  $sql .= ' ORDER BY p.created_at DESC LIMIT ? OFFSET ?';
  $params[] = $limit; $params[] = $offset;
  $stmt = db()->prepare($sql);
  $stmt->execute($params);
  return $stmt->fetchAll();
}
function get_product(int $id): ?array {
  $stmt = db()->prepare('SELECT p.*, c.name AS category_name
                         FROM products p
                         LEFT JOIN categories c ON p.category_id = c.id
                         WHERE p.id = ? LIMIT 1');
  $stmt->execute([$id]);
  $row = $stmt->fetch();
  return $row ?: null;
}
function get_product_images(int $productId): array {
  $stmt = db()->prepare('SELECT * FROM product_images WHERE product_id = ? ORDER BY created_at');
  $stmt->execute([$productId]);
  return $stmt->fetchAll();
}
function product_image_url(?string $path): string {
  if ($path && trim($path) !== '') return $path;
  // fallback placeholder
  return 'https://via.placeholder.com/640x480?text=No+Image';
}

// Cart (session-based)
function cart_init(): void {
  if (!isset($_SESSION['cart'])) $_SESSION['cart'] = []; // [product_id => qty]
}
function cart_add(int $productId, int $qty): void {
  cart_init();
  $qty = max(1, $qty);
  $_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + $qty;
}
function cart_update(int $productId, int $qty): void {
  cart_init();
  if ($qty <= 0) unset($_SESSION['cart'][$productId]);
  else $_SESSION['cart'][$productId] = $qty;
}
function cart_remove(int $productId): void {
  cart_init();
  unset($_SESSION['cart'][$productId]);
}
function cart_clear(): void {
  $_SESSION['cart'] = [];
}
function cart_items(): array {
  cart_init();
  if (empty($_SESSION['cart'])) return ['items' => [], 'subtotal' => 0.0];
  $ids = array_keys($_SESSION['cart']);
  $placeholders = implode(',', array_fill(0, count($ids), '?'));
  $stmt = db()->prepare("SELECT id, name, price, stock, main_image FROM products WHERE id IN ($placeholders)");
  $stmt->execute($ids);
  $byId = [];
  foreach ($stmt->fetchAll() as $p) $byId[$p['id']] = $p;

  $items = [];
  $subtotal = 0.0;
  foreach ($_SESSION['cart'] as $pid => $qty) {
    $prod = $byId[$pid] ?? null;
    if (!$prod) continue;
    $qty = min($qty, (int)$prod['stock']);
    $line = $qty * (float)$prod['price'];
    $subtotal += $line;
    $items[] = [
      'id' => (int)$prod['id'],
      'name' => $prod['name'],
      'price' => (float)$prod['price'],
      'stock' => (int)$prod['stock'],
      'qty' => (int)$qty,
      'line_total' => $line,
      'image' => $prod['main_image'],
    ];
  }
  return ['items' => $items, 'subtotal' => $subtotal];
}

// Order
function place_order(int $userId): array {
  $cart = cart_items();
  if (empty($cart['items'])) return [false, 'Keranjang kosong'];

  $pdo = db();
  try {
    $pdo->beginTransaction();
    // Re-check stock and lock rows
    foreach ($cart['items'] as $it) {
      $stmt = $pdo->prepare('SELECT stock FROM products WHERE id = ? FOR UPDATE');
      $stmt->execute([$it['id']]);
      $stock = (int)($stmt->fetch()['stock'] ?? 0);
      if ($stock < $it['qty']) {
        $pdo->rollBack();
        return [false, 'Stok tidak mencukupi untuk: '.esc($it['name'])];
      }
    }
    // Insert order
    $stmt = $pdo->prepare('INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, ?)');
    $stmt->execute([$userId, $cart['subtotal'], 'pending']);
    $orderId = (int)$pdo->lastInsertId();

    // Insert items & reduce stock
    $stmtItem = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
    $stmtStock = $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');
    foreach ($cart['items'] as $it) {
      $stmtItem->execute([$orderId, $it['id'], $it['qty'], $it['price']]);
      $stmtStock->execute([$it['qty'], $it['id']]);
    }

    $pdo->commit();
    cart_clear();
    return [true, $orderId];
  } catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log($e->getMessage()); // Log error for debugging
    return [false, 'Gagal memproses pesanan. Silakan coba lagi.'];
  }
}

// Otentikasi Admin
function is_admin(): bool {
  $user = current_user();
  return $user && isset($user['is_admin']) && $user['is_admin'];
}

function require_admin(): void {
  if (!is_admin()) {
    http_response_code(403);
    exit('Akses ditolak. Anda harus menjadi admin.');
  }
}

// CRUD Produk
function create_product(string $name, string $desc, float $price, int $stock, ?int $catId, ?string $image): int {
  $stmt = db()->prepare('INSERT INTO products (name, description, price, stock, category_id, main_image) VALUES (?, ?, ?, ?, ?, ?)');
  $stmt->execute([$name, $desc, $price, $stock, $catId, $image]);
  return (int)db()->lastInsertId();
}

function update_product(int $id, string $name, string $desc, float $price, int $stock, ?int $catId, ?string $image): bool {
  $sql = 'UPDATE products SET name=?, description=?, price=?, stock=?, category_id=?, main_image=? WHERE id=?';
  $stmt = db()->prepare($sql);
  return $stmt->execute([$name, $desc, $price, $stock, $catId, $image, $id]);
}

function delete_product(int $id): bool {
  $stmt = db()->prepare('DELETE FROM products WHERE id = ?');
  return $stmt->execute([$id]);
}

// CRUD Kategori
function get_category(int $id): ?array {
    $stmt = db()->prepare('SELECT * FROM categories WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function create_category(string $name, string $desc): int {
  $stmt = db()->prepare('INSERT INTO categories (name, description) VALUES (?, ?)');
  $stmt->execute([$name, $desc]);
  return (int)db()->lastInsertId();
}

function update_category(int $id, string $name, string $desc): bool {
  $stmt = db()->prepare('UPDATE categories SET name=?, description=? WHERE id=?');
  return $stmt->execute([$name, $desc, $id]);
}

function delete_category(int $id): bool {
  $stmt = db()->prepare('DELETE FROM categories WHERE id = ?');
  return $stmt->execute([$id]);
}

// Pesanan (Orders)
function get_all_orders(int $limit = 20, int $offset = 0): array {
    $sql = 'SELECT o.*, u.name as user_name, u.email as user_email
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC LIMIT ? OFFSET ?';
    $stmt = db()->prepare($sql);
    $stmt->execute([$limit, $offset]);
    return $stmt->fetchAll();
}

function count_orders(): int {
    return (int)db()->query('SELECT COUNT(*) FROM orders')->fetchColumn();
}