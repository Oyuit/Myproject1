<?php
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/csrf.php';

seed_default_admin($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = (string)($_POST['password'] ?? '');
    if (admin_login($pdo, $username, $password)) {
        header("Location: dashboard.php"); exit;
    } else { $err = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง"; }
}
?>
<!doctype html>
<html lang="th"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/styles.css" rel="stylesheet">
</head><body class="d-flex align-items-center" style="min-height:100vh">
<div class="container" style="max-width:420px">
  <div class="card shadow-sm">
    <div class="card-body">
      <h1 class="h4 fw-bold mb-3">เข้าสู่ระบบแอดมิน</h1>
      <?php if (!empty($err)): ?><div class="alert alert-danger"><?= h($err) ?></div><?php endif; ?>
      <form method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label">ชื่อผู้ใช้</label>
          <input name="username" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">รหัสผ่าน</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn btn-primary w-100">เข้าสู่ระบบ</button>
      </form>
    </div>
  </div>
</div>
</body></html>
