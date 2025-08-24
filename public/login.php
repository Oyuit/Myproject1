<?php
require_once __DIR__ . '/_header.php';
require_once __DIR__ . '/../app/csrf.php';
require_once __DIR__ . '/../app/flash.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = trim($_POST['email'] ?? '');
    $password = (string)($_POST['password'] ?? '');
    if (customer_login($pdo, $email, $password)) {
        header("Location: index.php");
        exit;
    } else {
        set_flash('err', 'อีเมลหรือรหัสผ่านไม่ถูกต้อง');
        header("Location: login.php"); exit;
    }
}
?>
<h1 class="h3 fw-bold">เข้าสู่ระบบลูกค้า</h1>
<?php if ($m = get_flash('err')): ?><div class="alert alert-danger"><?= h($m) ?></div><?php endif; ?>
<form method="post" class="mt-3" style="max-width:420px">
  <?= csrf_field() ?>
  <div class="mb-3">
    <label class="form-label">อีเมล</label>
    <input type="email" name="email" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">รหัสผ่าน</label>
    <input type="password" name="password" class="form-control" required>
  </div>
  <button class="btn btn-primary"><i class="fa fa-right-to-bracket me-1"></i> เข้าสู่ระบบ</button>
  <a class="btn btn-outline-light ms-2" href="register.php">สมัครสมาชิก</a>
</form>
<?php require_once __DIR__ . '/_footer.php'; ?>
