<?php
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/functions.php';
require_once __DIR__ . '/../app/auth.php';
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Akkrasin Parts</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <link href="../assets/css/styles.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark border-bottom border-secondary sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">
      <i class="fa-solid fa-motorcycle me-2"></i> Akkrasin 87
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <form class="ms-lg-3 my-3 my-lg-0 d-flex" method="get" action="index.php">
        <input class="form-control me-2" type="search" name="q" placeholder="ค้นหาสินค้า...">
        <button class="btn btn-outline-light"><i class="fa fa-search"></i></button>
      </form>
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item me-2">
          <a class="nav-link" href="cart.php">
            <i class="fa-solid fa-cart-shopping me-1"></i>
            ตะกร้า <span class="badge text-bg-light ms-1"><?= cart_count(); ?></span>
          </a>
        </li>
        <?php if (customer_logged_in()): ?>
          <li class="nav-item me-2"><a class="nav-link" href="orders.php"><i class="fa fa-receipt me-1"></i> คำสั่งซื้อของฉัน</a></li>
          <li class="nav-item"><a class="btn btn-outline-light" href="logout.php"><i class="fa fa-right-from-bracket me-1"></i> ออกจากระบบ</a></li>
        <?php else: ?>
          <li class="nav-item me-2"><a class="nav-link" href="login.php">เข้าสู่ระบบ</a></li>
          <li class="nav-item"><a class="btn btn-primary" href="register.php">สมัครสมาชิก</a></li>
        <?php endif; ?>
        <li class="nav-item ms-lg-3">
          <a class="btn btn-outline-info" href="../admin/login.php"><i class="fa-solid fa-user-gear me-1"></i> แอดมิน</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="container my-4">
