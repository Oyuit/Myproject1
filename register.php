<?php
session_start();
include 'connect.php';

// ตรวจสอบตัวแปรการเชื่อมต่อว่าถูกกำหนดไว้ใน connect.php
if (!isset($host, $user, $password, $dbname)) {
    die("Environment variables for DB not set properly.");
}

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// สร้าง CSRF Token ถ้ายังไม่มี
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = $success = '';

// กรณี POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ตรวจสอบ CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "CSRF token ไม่ถูกต้อง";
    } else {
        // รับค่าจากฟอร์มและกรองข้อมูล
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $name = trim($_POST['name'] ?? '');
        $role = $_POST['role'] ?? '';

        // ตรวจสอบข้อมูล
        if ($password !== $confirm_password) {
            $error = "รหัสผ่านไม่ตรงกัน";
        } elseif (strlen($password) < 6) {
            $error = "รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร";
        } elseif (!in_array($role, ['admin', 'staff'])) {
            $error = "สิทธิ์การใช้งานไม่ถูกต้อง";
        } else {
            // ตรวจสอบชื่อผู้ใช้ซ้ำ
            $check = $conn->prepare("SELECT 1 FROM users WHERE username = ?");
            $check->bind_param("s", $username);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $error = "ชื่อผู้ใช้นี้ถูกใช้ไปแล้ว";
            } else {
                // เข้ารหัสและบันทึก
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, password, name, role) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $hash, $name, $role);
                if ($stmt->execute()) {
                    $_SESSION['success'] = "สมัครสมาชิกเรียบร้อย! กรุณาเข้าสู่ระบบ";
                    header("Location: login.php");
                    exit;
                } else {
                    $error = "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error;
                }
                $stmt->close();
            }
            $check->close();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>สมัครสมาชิกลูกค้า - MotoParts Pro</title>
    <style>
        body {
            font-family: Arial, sans-serif; background: #f2f2f2;
            display: flex; justify-content: center; align-items: center; height: 100vh;
        }
        .register-box {
            background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 400px;
        }
        h2 { text-align: center; margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input {
            width: 100%; padding: 10px; margin-bottom: 15px;
            border: 1px solid #ccc; border-radius: 5px;
        }
        input[type="submit"] {
            background: #007bff; color: white; border: none; cursor: pointer;
        }
        .error { color: red; text-align: center; margin-bottom: 10px; }
        .success { color: green; text-align: center; margin-bottom: 10px; }
        .link { text-align: center; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="register-box">
        <h2>สมัครสมาชิกลูกค้า</h2>
        <?php if (!empty($error)) echo "<div class='error'>" . htmlspecialchars($error) . "</div>"; ?>
        <?php if (!empty($_SESSION['success'])) {
            echo "<div class='success'>" . htmlspecialchars($_SESSION['success']) . "</div>";
            unset($_SESSION['success']);
        } ?>
        <form method="POST" action="">
            <label>ชื่อเต็ม:</label>
            <input type="text" name="name" required>

            <label>ชื่อผู้ใช้:</label>
            <input type="text" name="username" required>

            <label>รหัสผ่าน:</label>
            <input type="password" name="password" required>

            <label>ยืนยันรหัสผ่าน:</label>
            <input type="password" name="confirm_password" required>

            <input type="submit" value="สมัครสมาชิก">
        </form>
        <div class="link">
            มีบัญชีแล้ว? <a href="login.php">เข้าสู่ระบบ</a>
        </div>
    </div>
</body>
</html>

