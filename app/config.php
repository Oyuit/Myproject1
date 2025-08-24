<?php
// app/config.php
// === Database Config ===
// ปรับค่าตาม XAMPP ของคุณ
define('DB_HOST', 'localhost');
define('DB_NAME', 'akkrasin');
define('DB_USER', 'root');
define('DB_PASS', '');

// Error & Session
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

try {
    $pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
        DB_USER, DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    die("Database connection failed: " . $e->getMessage());
}

// Base URL helper (works for /public and /admin)
function base_url(string $path = ''): string {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptDir = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    $base = $scheme . '://' . $host . $scriptDir;
    return rtrim($base, '/') . '/' . ltrim($path, '/');
}
