<?php require_once '_header.php'; require_admin(); require_once __DIR__.'/../app/csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['create'])) {
    $username = trim($_POST['username'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $role = $_POST['role'] ?? 'staff';
    $pass = (string)($_POST['password'] ?? '');
    if ($username && $pass) {
      $hash = password_hash($pass, PASSWORD_BCRYPT);
      $st = $pdo->prepare("INSERT INTO users (username, password, name, role) VALUES (?,?,?,?)");
      try { $st->execute([$username,$hash,$name,$role]); } catch (Exception $e) {}
    }
  } elseif (isset($_POST['delete'])) {
    $id = (int)$_POST['user_id'];
    $pdo->prepare("DELETE FROM users WHERE user_id=?")->execute([$id]);
  }
  header("Location: users.php"); exit;
}

$rows = $pdo->query("SELECT * FROM users ORDER BY user_id DESC")->fetchAll();
?>
<div class="d-flex align-items-center justify-content-between">
  <h1 class="h4 fw-bold">ผู้ใช้ระบบ</h1>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create"><i class="fa fa-user-plus me-1"></i> เพิ่มผู้ใช้</button>
</div>

<div class="table-responsive mt-3">
  <table class="table table-dark align-middle">
    <thead><tr><th>#</th><th>ชื่อผู้ใช้</th><th>ชื่อ</th><th>บทบาท</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($rows as $u): ?>
      <tr>
        <td><?= (int)$u['user_id'] ?></td>
        <td><?= h($u['username']) ?></td>
        <td><?= h($u['name']) ?></td>
        <td><span class="badge text-bg-secondary"><?= h($u['role']) ?></span></td>
        <td class="text-end">
          <?php if ($u['username'] !== 'admin'): ?>
          <form method="post" class="d-inline" onsubmit="return confirm('ลบผู้ใช้?')">
            <input type="hidden" name="user_id" value="<?= (int)$u['user_id'] ?>">
            <button class="btn btn-sm btn-outline-danger" name="delete" value="1"><i class="fa fa-trash"></i></button>
          </form>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Create Modal -->
<div class="modal fade" id="create">
  <div class="modal-dialog"><div class="modal-content">
    <form method="post">
      <div class="modal-header"><h5 class="modal-title">เพิ่มผู้ใช้</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-2"><label class="form-label">ชื่อผู้ใช้</label><input name="username" class="form-control" required></div>
        <div class="mb-2"><label class="form-label">ชื่อ</label><input name="name" class="form-control"></div>
        <div class="mb-2"><label class="form-label">รหัสผ่าน</label><input type="password" name="password" class="form-control" required></div>
        <div class="mb-2"><label class="form-label">บทบาท</label>
          <select name="role" class="form-select">
            <option value="admin">admin</option>
            <option value="staff" selected>staff</option>
          </select>
        </div>
      </div>
      <div class="modal-footer"><button class="btn btn-primary" name="create" value="1">บันทึก</button></div>
    </form>
  </div></div>
</div>

<?php require_once '_footer.php'; ?>
