<?php require_once '_header.php'; require_admin();
$prod = (int)$pdo->query("SELECT COUNT(*) c FROM products")->fetch()['c'];
$ord = (int)$pdo->query("SELECT COUNT(*) c FROM orders")->fetch()['c'];
$rev = (float)$pdo->query("SELECT IFNULL(SUM(total_price),0) s FROM orders WHERE status IN ('paid','shipped')")->fetch()['s'];
?>
<div class="d-flex align-items-center justify-content-between">
  <h1 class="h4 fw-bold">แดชบอร์ด</h1>
</div>
<div class="row g-3 mt-1">
  <div class="col-md-4">
    <div class="card"><div class="card-body"><div class="small text-muted">จำนวนสินค้า</div><div class="h3 mb-0"><?= $prod ?></div></div></div>
  </div>
  <div class="col-md-4">
    <div class="card"><div class="card-body"><div class="small text-muted">คำสั่งซื้อทั้งหมด</div><div class="h3 mb-0"><?= $ord ?></div></div></div>
  </div>
  <div class="col-md-4">
    <div class="card"><div class="card-body"><div class="small text-muted">รายได้สะสม</div><div class="h3 mb-0">฿<?= money($rev) ?></div></div></div>
  </div>
</div>
<?php require_once '_footer.php'; ?>
