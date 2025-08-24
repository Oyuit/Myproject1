<?php
require_once __DIR__ . '/_header.php';

if (!$email = ($_GET['email'] ?? '')) {
  // if logged in customer, use their email; else accept query ?email=
  if (customer_logged_in()) $email = customer_current()['email'] ?? '';
}
$success = isset($_GET['success']);
$order_id = (int)($_GET['id'] ?? 0);
?>
<div class="d-flex align-items-center justify-content-between">
  <h1 class="h4 fw-bold">ติดตามคำสั่งซื้อ</h1>
  <form class="d-flex" method="get">
    <input type="email" name="email" class="form-control me-2" placeholder="อีเมลลูกค้า" value="<?= h($email) ?>">
    <button class="btn btn-outline-light">ค้นหา</button>
  </form>
</div>
<?php if ($success): ?>
  <div class="alert alert-success mt-3">สั่งซื้อเรียบร้อย! หมายเลขคำสั่งซื้อ #<?= (int)$order_id ?></div>
<?php endif; ?>
<?php
if ($email) {
  $st = $pdo->prepare("SELECT * FROM customers WHERE email=? LIMIT 1");
  $st->execute([$email]); $c = $st->fetch();
  if ($c) {
    $cid = (int)$c['customer_id'];
    $orders = $pdo->prepare("SELECT * FROM orders WHERE customer_id=? ORDER BY order_id DESC");
    $orders->execute([$cid]); $orders = $orders->fetchAll();
    if (!$orders) { echo "<div class='alert alert-warning mt-3'>ยังไม่มีคำสั่งซื้อ</div>"; }
    else {
      echo '<div class="table-responsive mt-3"><table class="table table-dark"><thead><tr><th>#</th><th>วันที่</th><th>สถานะ</th><th>ยอดรวม</th><th>การชำระเงิน</th></tr></thead><tbody>';
      foreach ($orders as $o) {
        echo '<tr>';
        echo '<td>#'.(int)$o['order_id'].'</td>';
        echo '<td>'.h($o['order_date']).'</td>';
        echo '<td><span class="badge bg-secondary">'.h($o['status']).'</span></td>';
        echo '<td class="text-end">฿'.money($o['total_price']).'</td>';
        echo '<td>'.h($o['payment_method'] ?? '-').'</td>';
        echo '</tr>';
      }
      echo '</tbody></table></div>';
    }
  } else {
    echo '<div class="alert alert-danger mt-3">ไม่พบลูกค้าตามอีเมลนี้</div>';
  }
} else {
  echo '<div class="alert alert-info mt-3">กรอกอีเมลเพื่อตรวจสอบคำสั่งซื้อของคุณ</div>';
}
?>
<?php require_once __DIR__ . '/_footer.php'; ?>
