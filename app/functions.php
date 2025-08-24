<?php
// app/functions.php
function h($str) { return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8'); }
function money($num) { return number_format((float)$num, 2); }

function cart_count(): int {
    $c = 0;
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $pid => $qty) $c += (int)$qty;
    }
    return $c;
}
function cart_items(): array {
    return $_SESSION['cart'] ?? [];
}
function add_to_cart(int $pid, int $qty=1): void {
    if ($qty < 1) $qty = 1;
    if (empty($_SESSION['cart'][$pid])) $_SESSION['cart'][$pid] = 0;
    $_SESSION['cart'][$pid] += $qty;
}
function set_cart_qty(int $pid, int $qty): void {
    if ($qty <= 0) { unset($_SESSION['cart'][$pid]); }
    else { $_SESSION['cart'][$pid] = $qty; }
}
function clear_cart(): void { unset($_SESSION['cart']); }
