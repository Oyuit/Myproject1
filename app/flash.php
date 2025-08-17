<?php
// app/flash.php
function set_flash(string $key, string $message): void {
    $_SESSION['flash'][$key] = $message;
}
function get_flash(string $key): ?string {
    if (!empty($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}
