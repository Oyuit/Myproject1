<?php
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/functions.php';
require_once __DIR__ . '/../app/auth.php';
seed_default_admin($pdo);
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin • Akkrasin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <link href="../assets/css/styles.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <aside class="col-12 col-md-3 col-lg-2 p-3 sidebar">
      <div class="d-flex align-items-center gap-2 mb-3">
        <div class="avatar"><i class="fa fa-gear"></i></div>
        <div>
          <div class="fw-bold">Akkrasin Admin</div>
          <div class="small text-muted"><?= h(admin_current()['name'] ?? 'Guest') ?></div>
        </div>
      </div>
      <nav class="d-grid gap-1">
        <a href="dashboard.php" class="<?= basename($_SERVER['SCRIPT_NAME'])==='dashboard.php'?'active':'' ?>"><i class="fa fa-gauge me-2"></i> แดชบอร์ด</a>
        <a href="products.php" class="<?= basename($_SERVER['SCRIPT_NAME'])==='products.php'?'active':'' ?>"><i class="fa fa-boxes-stacked me-2"></i> สินค้า</a>
        <a href="orders.php" class="<?= basename($_SERVER['SCRIPT_NAME'])==='orders.php'?'active':'' ?>"><i class="fa fa-receipt me-2"></i> คำสั่งซื้อ</a>
        <a href="reports.php" class="<?= basename($_SERVER['SCRIPT_NAME'])==='reports.php'?'active':'' ?>"><i class="fa fa-chart-line me-2"></i> รายงาน</a>
        <a href="users.php" class="<?= basename($_SERVER['SCRIPT_NAME'])==='users.php'?'active':'' ?>"><i class="fa fa-users-gear me-2"></i> ผู้ใช้</a>
        <a href="logout.php"><i class="fa fa-right-from-bracket me-2"></i> ออกจากระบบ</a>
      </nav>
    </aside>
    <main class="col-12 col-md-9 col-lg-10 p-4">
