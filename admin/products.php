<?php require_once '_header.php'; require_once __DIR__ . '/../app/csrf.php'; require_admin();

// Handle create/update/delete
$upload_dir = realpath(__DIR__ . '/../uploads');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['create']) || isset($_POST['update'])) {
    $id = (int)($_POST['product_id'] ?? 0);
    $name = trim($_POST['product_name'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $qty = (int)($_POST['quantity'] ?? 0);
    $desc = trim($_POST['description'] ?? '');
    $model = !empty($_POST['compatible_model_id']) ? (int)$_POST['compatible_model_id'] : null;
    $imgname = null;
    if (!empty($_FILES['image']['name'])) {
      $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
      if (in_array($ext, ['jpg','jpeg','png','webp'])) {
        $imgname = time().'_'.preg_replace('/[^a-z0-9\.]+/i','_', $_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . '/' . $imgname);
      }
    }
    if (isset($_POST['create'])) {
      $st = $pdo->prepare("INSERT INTO products (product_name, description, price, quantity, compatible_model_id, image_url) VALUES (?,?,?,?,?,?)");
      $st->execute([$name,$desc,$price,$qty,$model,$imgname]);
    } else {
      if ($imgname) {
        $st = $pdo->prepare("UPDATE products SET product_name=?, description=?, price=?, quantity=?, compatible_model_id=?, image_url=? WHERE product_id=?");
        $st->execute([$name,$desc,$price,$qty,$model,$imgname,$id]);
      } else {
        $st = $pdo->prepare("UPDATE products SET product_name=?, description=?, price=?, quantity=?, compatible_model_id=? WHERE product_id=?");
        $st->execute([$name,$desc,$price,$qty,$model,$id]);
      }
    }
  } elseif (isset($_POST['delete'])) {
    $id = (int)$_POST['product_id'];
    $pdo->prepare("DELETE FROM products WHERE product_id=?")->execute([$id]);
  }
  header("Location: products.php"); exit;
}

$models = $pdo->query("SELECT m.model_id, m.model_name, b.brand_name FROM motorcycle_models m LEFT JOIN brands b ON m.brand_id=b.brand_id ORDER BY b.brand_name, m.model_name")->fetchAll();
$rows = $pdo->query("SELECT p.*, m.model_name, b.brand_name FROM products p LEFT JOIN motorcycle_models m ON p.compatible_model_id=m.model_id LEFT JOIN brands b ON m.brand_id=b.brand_id ORDER BY p.product_id DESC")->fetchAll();
?>
<div class="d-flex align-items-center justify-content-between">
  <h1 class="h4 fw-bold">จัดการสินค้า</h1>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate"><i class="fa fa-plus me-1"></i> เพิ่มสินค้า</button>
</div>

<div class="table-responsive mt-3">
  <table class="table table-dark align-middle">
    <thead><tr><th>#</th><th>สินค้า</th><th>รุ่น</th><th class="text-end">ราคา</th><th class="text-end">สต๊อก</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= (int)$r['product_id'] ?></td>
        <td class="fw-semibold"><?= h($r['product_name']) ?></td>
        <td><?= h(($r['brand_name']??'-').' • '.($r['model_name']??'-')) ?></td>
        <td class="text-end">฿<?= money($r['price']) ?></td>
        <td class="text-end"><?= (int)$r['quantity'] ?></td>
        <td class="text-end">
          <button class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#modalEdit<?= (int)$r['product_id'] ?>"><i class="fa fa-pen"></i></button>
          <form method="post" class="d-inline" onsubmit="return confirm('ลบสินค้า?')">
            <input type="hidden" name="product_id" value="<?= (int)$r['product_id'] ?>">
            <button class="btn btn-sm btn-outline-danger" name="delete" value="1"><i class="fa fa-trash"></i></button>
          </form>
        </td>
      </tr>

      <!-- Edit Modal -->
      <div class="modal fade" id="modalEdit<?= (int)$r['product_id'] ?>">
        <div class="modal-dialog"><div class="modal-content">
          <form method="post" enctype="multipart/form-data">
            <div class="modal-header"><h5 class="modal-title">แก้ไขสินค้า</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
              <input type="hidden" name="product_id" value="<?= (int)$r['product_id'] ?>">
              <div class="mb-2"><label class="form-label">ชื่อสินค้า</label><input name="product_name" class="form-control" value="<?= h($r['product_name']) ?>" required></div>
              <div class="mb-2"><label class="form-label">รายละเอียด</label><textarea name="description" class="form-control" rows="3"><?= h($r['description']) ?></textarea></div>
              <div class="row g-2">
                <div class="col-md-6"><label class="form-label">ราคา</label><input name="price" type="number" step="0.01" class="form-control" value="<?= h($r['price']) ?>" required></div>
                <div class="col-md-6"><label class="form-label">สต๊อก</label><input name="quantity" type="number" class="form-control" value="<?= (int)$r['quantity'] ?>" required></div>
              </div>
              <div class="mb-2"><label class="form-label">รุ่นรถที่รองรับ</label>
                <select name="compatible_model_id" class="form-select">
                  <option value="">-</option>
                  <?php foreach ($models as $m): $sel = ($r['compatible_model_id']==$m['model_id'])?'selected':''; ?>
                    <option value="<?= (int)$m['model_id'] ?>" <?= $sel ?>><?= h($m['brand_name'].' • '.$m['model_name']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="mb-2"><label class="form-label">รูปภาพ (อัปโหลดใหม่เพื่อแทนที่)</label><input type="file" name="image" class="form-control"></div>
            </div>
            <div class="modal-footer"><button class="btn btn-primary" name="update" value="1">บันทึก</button></div>
          </form>
        </div></div>
      </div>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Create Modal -->
<div class="modal fade" id="modalCreate">
  <div class="modal-dialog"><div class="modal-content">
    <form method="post" enctype="multipart/form-data">
      <div class="modal-header"><h5 class="modal-title">เพิ่มสินค้า</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-2"><label class="form-label">ชื่อสินค้า</label><input name="product_name" class="form-control" required></div>
        <div class="mb-2"><label class="form-label">รายละเอียด</label><textarea name="description" class="form-control" rows="3"></textarea></div>
        <div class="row g-2">
          <div class="col-md-6"><label class="form-label">ราคา</label><input name="price" type="number" step="0.01" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label">สต๊อก</label><input name="quantity" type="number" class="form-control" required></div>
        </div>
        <div class="mb-2"><label class="form-label">รุ่นรถที่รองรับ</label>
          <select name="compatible_model_id" class="form-select">
            <option value="">-</option>
            <?php foreach ($models as $m): ?>
              <option value="<?= (int)$m['model_id'] ?>"><?= h($m['brand_name'].' • '.$m['model_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-2"><label class="form-label">รูปภาพ</label><input type="file" name="image" class="form-control"></div>
      </div>
      <div class="modal-footer"><button class="btn btn-primary" name="create" value="1">บันทึก</button></div>
    </form>
  </div></div>
</div>

<?php require_once '_footer.php'; ?>
