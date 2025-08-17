<?php
require_once __DIR__ . '/_header.php';
require_once __DIR__ . '/../app/csrf.php';
require_once __DIR__ . '/../app/flash.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = (string)($_POST['password'] ?? '');
    if ($name && $email && $password) {
        if (customer_register($pdo, $name, $email, $phone, $address, $password)) {
            customer_login($pdo, $email, $password);
            header("Location: index.php"); exit;
        } else { $err = 'อีเมลนี้อาจถูกใช้แล้ว'; }
    } else { $err = 'กรอกข้อมูลให้ครบถ้วน'; }
}
?>
<h1 class="h3 fw-bold">สมัครสมาชิก</h1>
<?php if (!empty($err)): ?><div class="alert alert-danger"><?= h($err) ?></div><?php endif; ?>
<form method="post" class="mt-3" style="max-width:620px">
  <?= csrf_field() ?>
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">ชื่อ-นามสกุล</label>
      <input name="name" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">อีเมล</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">เบอร์โทร</label>
      <input name="phone" class="form-control">
    </div>
    <div class="col-12">
      <label class="form-label">ที่อยู่</label>
      <textarea name="address" class="form-control" rows="3"></textarea>
    </div>
    <div class="col-md-6">
      <label class="form-label">รหัสผ่าน</label>
      <input type="password" name="password" class="form-control" required>
    </div>
  </div>
  <div class="mt-3">
    <button class="btn btn-success"><i class="fa fa-user-plus me-1"></i> สมัครสมาชิก</button>
    <a class="btn btn-outline-light ms-2" href="login.php">เข้าสู่ระบบ</a>
  </div>
</form>
<?php require_once __DIR__ . '/_footer.php'; ?>
