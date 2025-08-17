<?php require_once '_header.php'; require_admin(); ensure_order_extra_columns($pdo);

$update_id = (int)($_POST['order_id'] ?? 0);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $update_id) {
  $status = $_POST['status'] ?? 'pending';
  $pdo->prepare("UPDATE orders SET status=? WHERE order_id=?")->execute([$status, $update_id]);
  header("Location: orders.php"); exit;
}

// list orders
$rows = $pdo->query("SELECT o.*, c.name, c.email, c.phone FROM orders o LEFT JOIN customers c ON o.customer_id=c.customer_id ORDER BY o.order_id DESC")->fetchAll();
?>
<div class="d-flex align-items-center justify-content-between">
  <h1 class="h4 fw-bold">คำสั่งซื้อ</h1>
</div>
<div class="table-responsive mt-3">
  <table class="table table-dark align-middle">
    <thead><tr><th>#</th><th>ลูกค้า</th><th>วันที่</th><th>ยอดรวม</th><th>ชำระเงิน</th><th>สถานะ</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($rows as $o): ?>
      <tr>
        <td>#<?= (int)$o['order_id'] ?></td>
        <td><?= h($o['name']) ?><div class="small text-muted"><?= h($o['phone']) ?> • <?= h($o['email']) ?></div></td>
        <td><?= h($o['order_date']) ?></td>
        <td class="text-end">฿<?= money($o['total_price']) ?></td>
        <td><?= h($o['payment_method'] ?? '-') ?></td>
        <td><span class="badge text-bg-secondary"><?= h($o['status']) ?></span></td>
        <td class="text-end">
          <button class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#view<?= (int)$o['order_id'] ?>"><i class="fa fa-eye"></i></button>
        </td>
      </tr>

      <!-- View/Update Modal -->
      <div class="modal fade" id="view<?= (int)$o['order_id'] ?>">
        <div class="modal-dialog modal-lg"><div class="modal-content">
          <div class="modal-header"><h5 class="modal-title">คำสั่งซื้อ #<?= (int)$o['order_id'] ?></h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <div class="card"><div class="card-body">
                  <div class="fw-bold">ข้อมูลลูกค้า</div>
                  <div><?= h($o['name']) ?></div>
                  <div class="small text-muted"><?= h($o['phone']) ?> • <?= h($o['email']) ?></div>
                  <div class="small">ที่อยู่จัดส่ง: <?= nl2br(h($o['shipping_address'] ?? '')) ?></div>
                </div></div>
              </div>
              <div class="col-md-6">
                <div class="card"><div class="card-body">
                  <div class="fw-bold mb-1">สรุปออเดอร์</div>
                  <div>ยอดรวม: ฿<?= money($o['total_price']) ?></div>
                  <div>วิธีชำระเงิน: <?= h($o['payment_method'] ?? '-') ?></div>
                  <div>สถานะ: <span class="badge text-bg-secondary"><?= h($o['status']) ?></span></div>
                </div></div>
              </div>
              <div class="col-12">
                <div class="card"><div class="card-body">
                  <div class="fw-bold mb-2">รายการสินค้า</div>
                  <div class="table-responsive">
                    <table class="table table-dark">
                      <thead><tr><th>สินค้า</th><th class="text-end">จำนวน</th><th class="text-end">ราคา/หน่วย</th><th class="text-end">รวม</th></tr></thead>
                      <tbody>
                      <?php 
                        $it = $pdo->prepare("SELECT oi.*, p.product_name FROM order_items oi LEFT JOIN products p ON oi.product_id=p.product_id WHERE oi.order_id=?");
                        $it->execute([$o['order_id']]); $its = $it->fetchAll();
                        foreach ($its as $r) {
                          $line = $r['quantity'] * $r['price'];
                          echo '<tr><td>'.h($r['product_name']).'</td><td class="text-end">'.(int)$r['quantity'].'</td><td class="text-end">฿'.money($r['price']).'</td><td class="text-end">฿'.money($line).'</td></tr>';
                        }
                      ?>
                      </tbody>
                    </table>
                  </div>
                </div></div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <form method="post" class="d-flex gap-2 align-items-center">
              <input type="hidden" name="order_id" value="<?= (int)$o['order_id'] ?>">
              <select name="status" class="form-select">
                <?php foreach (['pending','paid','shipped','cancelled'] as $s): $sel=$s===$o['status']?'selected':''; ?>
                  <option value="<?= $s ?>" <?= $sel ?>><?= $s ?></option>
                <?php endforeach; ?>
              </select>
              <button class="btn btn-primary">บันทึกสถานะ</button>
            </form>
          </div>
        </div></div>
      </div>

      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require_once '_footer.php'; ?>
