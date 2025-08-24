<?php
// app/auth.php
require_once __DIR__.'/config.php';

// ---- Admin ----
function admin_current() { return $_SESSION['admin'] ?? null; }
function admin_logged_in(): bool { return !empty($_SESSION['admin']); }
function require_admin(): void {
    if (!admin_logged_in()) { header("Location: login.php"); exit; }
}
function admin_login(PDO $pdo, string $username, string $password): bool {
    $st = $pdo->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
    $st->execute([$username]);
    $u = $st->fetch();
    if ($u && password_verify($password, $u['password'])) {
        $_SESSION['admin'] = $u;
        return true;
    }
    return false;
}
function admin_logout(): void { unset($_SESSION['admin']); }

// Seed default admin if users empty
function seed_default_admin(PDO $pdo): void {
    $count = (int)$pdo->query("SELECT COUNT(*) AS c FROM users")->fetch()['c'] ?? 0;
    if ($count === 0) {
        $hash = password_hash('admin123', PASSWORD_BCRYPT);
        $st = $pdo->prepare("INSERT INTO users (username, password, name, role) VALUES (?,?,?,?)");
        $st->execute(['admin', $hash, 'Administrator', 'admin']);
    }
}

// ---- Customer ----
function customer_current() { return $_SESSION['customer'] ?? null; }
function customer_logged_in(): bool { return !empty($_SESSION['customer']); }
function customer_logout(): void { unset($_SESSION['customer']); }

// add password column to customers if missing
function ensure_customer_auth_columns(PDO $pdo): void {
    $cols = $pdo->query("SHOW COLUMNS FROM customers")->fetchAll();
    $names = array_map(fn($c)=>$c['Field'], $cols);
    if (!in_array('password', $names)) {
        $pdo->exec("ALTER TABLE customers ADD COLUMN password varchar(255) NULL AFTER email");
    }
}

// Optional ecommerce columns for orders
function ensure_order_extra_columns(PDO $pdo): void {
    $cols = $pdo->query("SHOW COLUMNS FROM orders")->fetchAll();
    $names = array_map(fn($c)=>$c['Field'], $cols);
    if (!in_array('payment_method', $names)) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN payment_method varchar(50) NULL AFTER status");
    }
    if (!in_array('payment_slip', $names)) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN payment_slip varchar(255) NULL AFTER payment_method");
    }
    if (!in_array('shipping_address', $names)) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN shipping_address text NULL AFTER total_price");
    }
}

function customer_register(PDO $pdo, string $name, string $email, string $phone, string $address, string $password): bool {
    ensure_customer_auth_columns($pdo);
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $st = $pdo->prepare("INSERT INTO customers (name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
    try { return $st->execute([$name, $email, $hash, $phone, $address]); } catch (Exception $e) { return false; }
}
function customer_login(PDO $pdo, string $email, string $password): bool {
    ensure_customer_auth_columns($pdo);
    $st = $pdo->prepare("SELECT * FROM customers WHERE email = ? LIMIT 1");
    $st->execute([$email]);
    $c = $st->fetch();
    if ($c && !empty($c['password']) && password_verify($password, $c['password'])) {
        $_SESSION['customer'] = $c;
        return true;
    }
    return false;
}
