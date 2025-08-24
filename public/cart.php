<?php
require_once __DIR__ . '/_header.php';
require_once __DIR__ . '/../app/csrf.php';

// Handle updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    if (isset($_POST['update'])) {
        foreach ($_POST['qty'] ?? [] as $pid => $qty) {
            set_cart_qty((int)$pid, max(0, (int)$qty));
        }
    } elseif (isset($_POST['clear'])) {
        clear_cart();
    }
    header("Location: cart.php");
    exit;
}

$items = cart_items();
$products = [];
$total = 0;
if ($items) {
    $ids = implode(',', array_map('intval', array_keys($items)));
    $rows = $pdo->query("SELECT * FROM products WHERE product_id IN ($ids)")->fetchAll();
    foreach ($rows as $r) {
        $pid = (int)$r['product_id'];
        $qty = (int)($items[$pid] ?? 0);
        $line = $qty * (float)$r['price'];
        $r['_qty'] = $qty; $r['_line'] = $line;
        $total += $line;
        $products[$pid] = $r;
    }
}
?>
<h1 class="h3 fw-bold">ตะกร้าสินค้า</h1>
<?php if (!$items): ?>
  <div class="alert alert-warning">ยังไม่มีสินค้าในตะกร้า</div>
<?php else: ?>
<form method="post" class="mt-3">
  <?= csrf_field() ?>
  <div class="table-responsive">
    <table class="table table-dark align-middle">
      <thead><tr><th>สินค้า</th><th style="width:120px">จำนวน</th><th class="text-end">ราคา/ชิ้น</th><th class="text-end">รวม</th></tr></thead>
      <tbody>
        <?php foreach ($products as $p): ?>
          <tr>
            <td><?= h($p['product_name']) ?></td>
            <td><input type="number" name="qty[<?= (int)$p['product_id'] ?>]" min="0" value="<?= (int)$p['_qty'] ?>" class="form-control form-control-sm"></td>
            <td class="text-end">฿<?= money($p['price']) ?></td>
            <td class="text-end">฿<?= money($p['_line']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot><tr><th colspan="3" class="text-end">รวมทั้งสิ้น</th><th class="text-end">฿<?= money($total) ?></th></tr></tfoot>
    </table>
  </div>
  <div class="d-flex gap-2 justify-content-end">
    <button class="btn btn-outline-light" name="clear" value="1" onclick="return confirm('ล้างตะกร้า?')"><i class="fa fa-trash me-1"></i> ล้างตะกร้า</button>
    <button class="btn btn-primary" name="update" value="1"><i class="fa fa-rotate me-1"></i> อัปเดตจำนวน</button>
    <a class="btn btn-success" href="checkout.php"><i class="fa fa-check me-1"></i> ดำเนินการสั่งซื้อ</a>
  </div>
</form>
<?php endif; ?>
<?php require_once __DIR__ . '/_footer.php'; ?>
