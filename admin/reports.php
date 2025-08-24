<?php require_once '_header.php'; require_admin();
$byMonth = $pdo->query("SELECT DATE_FORMAT(order_date, '%Y-%m') ym, SUM(total_price) total FROM orders WHERE status IN ('paid','shipped') GROUP BY ym ORDER BY ym DESC")->fetchAll();
$best = $pdo->query("SELECT p.product_name, SUM(oi.quantity) qty FROM order_items oi LEFT JOIN products p ON oi.product_id=p.product_id GROUP BY oi.product_id ORDER BY qty DESC LIMIT 5")->fetchAll();
?>
<h1 class="h4 fw-bold">รายงาน</h1>
<div class="row g-3 mt-1">
  <div class="col-lg-6">
    <div class="card"><div class="card-body">
      <div class="fw-bold mb-2"><i class="fa fa-chart-column me-1"></i> ยอดขายรายเดือน</div>
      <ul class="list-group list-group-flush">
        <?php foreach ($byMonth as $r): ?>
          <li class="list-group-item bg-transparent d-flex justify-content-between">
            <span class="text-muted"><?= h($r['ym']) ?></span>
            <span class="fw-bold">฿<?= money($r['total']) ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div></div>
  </div>
  <div class="col-lg-6">
    <div class="card"><div class="card-body">
      <div class="fw-bold mb-2"><i class="fa fa-trophy me-1"></i> สินค้าขายดี</div>
      <ol class="mb-0">
        <?php foreach ($best as $r): ?>
          <li class="mb-1 d-flex justify-content-between"><span><?= h($r['product_name']) ?></span><span class="text-muted"><?= (int)$r['qty'] ?> ชิ้น</span></li>
        <?php endforeach; ?>
      </ol>
    </div></div>
  </div>
</div>
<?php require_once '_footer.php'; ?>
