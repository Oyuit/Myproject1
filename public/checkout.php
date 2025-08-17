<?php
require_once __DIR__ . '/_header.php';
require_once __DIR__ . '/../app/csrf.php';
require_once __DIR__ . '/../app/flash.php';

ensure_order_extra_columns($pdo);

$items = cart_items();
if (!$items) { echo "<div class='alert alert-warning'>ไม่มีสินค้าในตะกร้า</div>"; require_once '_footer.php'; exit; }

// load products
$ids = implode(',', array_map('intval', array_keys($items)));
$rows = $pdo->query("SELECT * FROM products WHERE product_id IN ($ids)")->fetchAll();
$map = []; $total = 0;
foreach ($rows as $r) {
    $pid = (int)$r['product_id'];
    $qty = (int)($items[$pid] ?? 0);
    $line = $qty * (float)$r['price'];
    $map[$pid] = $r + ['_qty'=>$qty, '_line'=>$line];
    $total += $line;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? 'COD');

    if ($name === '' || $phone === '' || $address === '') {
        set_flash('err', 'กรุณากรอกข้อมูลให้ครบ');
        header("Location: checkout.php"); exit;
    }

    // customer: create or reuse by email
    ensure_customer_auth_columns($pdo);
    $pdo->beginTransaction();
    try {
        $st = $pdo->prepare("SELECT * FROM customers WHERE email = ? LIMIT 1");
        $st->execute([$email]);
        $c = $st->fetch();
        if ($c) {
            $customer_id = (int)$c['customer_id'];
            $pdo->prepare("UPDATE customers SET name=?, phone=?, address=? WHERE customer_id=?")
                ->execute([$name,$phone,$address,$customer_id]);
        } else {
            $pdo->prepare("INSERT INTO customers (name,email,phone,address) VALUES (?,?,?,?)")
                ->execute([$name,$email,$phone,$address]);
            $customer_id = (int)$pdo->lastInsertId();
        }

        // create order
        $pdo->prepare("INSERT INTO orders (customer_id, status, payment_method, total_price, shipping_address) VALUES (?,?,?,?,?)")
            ->execute([$customer_id, 'pending', $payment_method, $total, $address]);
        $order_id = (int)$pdo->lastInsertId();

        // items
        $stItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?,?,?,?)");
        foreach ($map as $pid => $p) {
            $stItem->execute([$order_id, $pid, (int)$p['_qty'], (float)$p['price']]);
            // reduce stock
            $pdo->prepare("UPDATE products SET quantity = GREATEST(quantity - ?, 0) WHERE product_id=?")->execute([(int)$p['_qty'], $pid]);
        }

        $pdo->commit();
        clear_cart();
        header("Location: orders.php?success=1&id=".$order_id);
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        set_flash('err','เกิดข้อผิดพลาด: '.$e->getMessage());
        header("Location: checkout.php"); exit;
    }
}

?>
<h1 class="h3 fw-bold">ยืนยันการสั่งซื้อ</h1>
<?php if ($m = get_flash('err')): ?>
  <div class="alert alert-danger"><?= h($m) ?></div>
<?php endif; ?>
<div class="row g-4">
  <div class="col-lg-7">
    <form method="post">
      <?= csrf_field() ?>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">ชื่อ-นามสกุล</label>
          <input name="name" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">อีเมล (สำหรับติดตามสถานะ)</label>
          <input name="email" type="email" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">เบอร์โทร</label>
          <input name="phone" class="form-control" required>
        </div>
        <div class="col-12">
          <label class="form-label">ที่อยู่จัดส่ง</label>
          <textarea name="address" class="form-control" rows="3" required></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">วิธีชำระเงิน</label>
          <select name="payment_method" class="form-select">
            <option value="TRANSFER">โอนเงิน</option>
            <option value="COD">เก็บเงินปลายทาง</option>
            <option value="CARD">บัตรเครดิต (Demo)</option>
          </select>
        </div>
      </div>
      <div class="mt-3 d-flex gap-2">
        <a class="btn btn-outline-light" href="cart.php"><i class="fa fa-chevron-left me-1"></i> กลับไปตะกร้า</a>
        <button class="btn btn-success"><i class="fa fa-check me-1"></i> ยืนยันคำสั่งซื้อ</button>
      </div>
    </form>
  </div>
  <div class="col-lg-5">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">สรุปรายการ</h5>
        <ul class="list-unstyled">
          <?php foreach ($map as $p): ?>
            <li class="d-flex justify-content-between"><span><?= h($p['product_name']) ?> × <?= (int)$p['_qty'] ?></span><span>฿<?= money($p['_line']) ?></span></li>
          <?php endforeach; ?>
        </ul>
        <hr>
        <div class="d-flex justify-content-between fw-bold"><span>รวมทั้งสิ้น</span><span>฿<?= money($total) ?></span></div>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/_footer.php'; ?>
