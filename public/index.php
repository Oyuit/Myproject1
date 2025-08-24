<?php
require_once __DIR__ . '/_header.php';

$q = trim($_GET['q'] ?? '');
$sql = "SELECT p.*, m.model_name, b.brand_name 
        FROM products p 
        LEFT JOIN motorcycle_models m ON p.compatible_model_id = m.model_id
        LEFT JOIN brands b ON m.brand_id = b.brand_id";
$params = [];
if ($q !== '') { 
  $sql .= " WHERE p.product_name LIKE ? OR p.description LIKE ?";
  $params = ["%$q%","%$q%"]; 
}
$sql .= " ORDER BY p.product_id DESC";
$st = $pdo->prepare($sql); 
$st->execute($params); 
$products = $st->fetchAll();
?>
<div class="d-flex align-items-center justify-content-between">
  <h1 class="h3 fw-bold">สินค้า</h1>
  <div class="text-muted">พบ <?= count($products) ?> รายการ</div>
</div>

<div class="row g-3 mt-1">
<?php foreach ($products as $p): ?>
  <?php
  /* รูปภาพสินค้า:
   * - ถ้ามีชื่อไฟล์ในฐานข้อมูล จะใช้ไฟล์นั้น
   * - แต่ถ้าไฟล์หาย/พาธผิด ให้ fallback เป็น no-image.png
   * - ใช้ basename() เพื่อกัน path แปลกๆ ที่ผู้ใช้ใส่มา
   */
  $imgRel = "../uploads/" . basename($p['image_url'] ?? '');
  $imgFs  = realpath(__DIR__ . "/../uploads/" . basename($p['image_url'] ?? ''));
  if (empty($p['image_url']) || !$imgFs || !file_exists($imgFs)) {
      $imgRel = "../uploads/no-image.png";
  }
  $img = $imgRel;
  ?>
  <div class="col-12 col-md-6 col-lg-4">
    <div class="card h-100 shadow-sm">
      <!-- แสดงรูปสินค้า; ถ้าไฟล์หายจะ fallback เป็น no-image.png -->
      <img src="<?= h($img) ?>" class="card-img-top" alt="<?= h($p['product_name']) ?>" loading="lazy">
      <div class="card-body d-flex flex-column">
        <div class="small text-muted mb-1">
          <?= h($p['brand_name'] ?? '-') ?> • <?= h($p['model_name'] ?? '-') ?>
        </div>
        <h5 class="card-title"><?= h($p['product_name']) ?></h5>
        <p class="card-text text-muted"><?= nl2br(h(mb_strimwidth($p['description'] ?? '', 0, 120, '...'))) ?></p>
        <div class="mt-auto d-flex justify-content-between align-items-center">
          <span class="h5 mb-0">฿<?= money($p['price']) ?></span>
          <a class="btn btn-outline-light" href="product.php?id=<?= (int)$p['product_id'] ?>">
            ดูรายละเอียด <i class="fa fa-arrow-right ms-1"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/_footer.php'; ?>
