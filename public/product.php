<?php
require_once __DIR__ . '/_header.php';
require_once __DIR__ . '/../app/csrf.php';

$id = (int)($_GET['id'] ?? 0);
$st = $pdo->prepare("SELECT p.*, m.model_name, b.brand_name 
    FROM products p 
    LEFT JOIN motorcycle_models m ON p.compatible_model_id = m.model_id
    LEFT JOIN brands b ON m.brand_id = b.brand_id
    WHERE p.product_id = ?");
$st->execute([$id]);
$p = $st->fetch();
if (!$p) { echo "<div class='alert alert-danger'>ไม่พบสินค้า</div>"; require_once '_footer.php'; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $qty = max(1, (int)($_POST['qty'] ?? 1));
    add_to_cart($p['product_id'], $qty);
    header("Location: cart.php");
    exit;
}

$img = $p['image_url'] ? "../uploads/".h($p['image_url']) : "../uploads/no-image.png";
?>
<div class="row g-4">
  <div class="col-md-6">
    <!-- แสดงรูปสินค้าแบบใหญ่; มี fallback เช่นกัน -->
    <img src="<?= h($img) ?>" class="w-100 rounded" alt="<?= h($p['product_name']) ?>" loading="lazy">
  </div>
  <div class="col-md-6">
    <h1 class="h3"><?= h($p['product_name']) ?></h1>
    <div class="text-muted mb-2"><?= h($p['brand_name'] ?? '-') ?> • <?= h($p['model_name'] ?? '-') ?></div>
    <p class="text-muted"><?= nl2br(h($p['description'] ?? '')) ?></p>
    <div class="h4">฿<?= money($p['price']) ?></div>
    <form method="post" class="mt-3">
      <?= csrf_field() ?>
      <div class="input-group" style="max-width:220px">
        <input type="number" min="1" name="qty" value="1" class="form-control">
        <button class="btn btn-primary"><i class="fa fa-cart-plus me-1"></i> ใส่ตะกร้า</button>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/_footer.php'; ?>
