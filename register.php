<?php
session_start();

// เชื่อมต่อฐานข้อมูล
$host = "localhost";
$user = "root";
$pass = "";
$db = "akkrasin";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// กดสมัคร
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $name = trim($_POST['name']);
    $role = $_POST['role'];

    if ($password !== $confirm_password) {
        $error = "รหัสผ่านไม่ตรงกัน";
    } else {
        // ตรวจสอบว่าชื่อผู้ใช้ซ้ำหรือไม่
        $check = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $error = "ชื่อผู้ใช้นี้ถูกใช้ไปแล้ว";
        } else {
            // บันทึกข้อมูล (แนะนำให้เข้ารหัสรหัสผ่าน)
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password, name, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $hash, $name, $role);
            $stmt->execute();

            $success = "สมัครสมาชิกเรียบร้อย! กรุณาเข้าสู่ระบบ";
            header("refresh:2;url=login.php");
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>สมัครสมาชิก - MotoParts Pro</title>
    <style>
        body {
            font-family: Arial, sans-serif; background: #f2f2f2;
            display: flex; justify-content: center; align-items: center; height: 100vh;
        }

        .register-box {
            background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 400px;
        }

        h2 {
            text-align: center; margin-bottom: 20px;
        }

        label {
            display: block; margin-bottom: 5px; font-weight: bold;
        }

        input, select {
            width: 100%; padding: 10px; margin-bottom: 15px;
            border: 1px solid #ccc; border-radius: 5px;
        }

        input[type="submit"] {
            background: #28a745; color: white; border: none; cursor: pointer;
        }

        .error { color: red; text-align: center; margin-bottom: 10px; }
        .success { color: green; text-align: center; margin-bottom: 10px; }
        .link { text-align: center; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="register-box">
        <h2>สมัครสมาชิกผู้ดูแล</h2>
        <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
        <?php if (!empty($success)) echo "<div class='success'>$success</div>"; ?>
        <form method="POST" action="">
            <label>ชื่อเต็ม:</label>
            <input type="text" name="name" required>

            <label>ชื่อผู้ใช้:</label>
            <input type="text" name="username" required>

            <label>รหัสผ่าน:</label>
            <input type="password" name="password" required>

            <label>ยืนยันรหัสผ่าน:</label>
            <input type="password" name="confirm_password" required>

            <label>สิทธิ์การใช้งาน:</label>
            <select name="role" required>
                <option value="admin">ผู้ดูแลระบบ (admin)</option>
                <option value="staff">พนักงาน (staff)</option>
            </select>

            <input type="submit" value="สมัครสมาชิก">
        </form>
        <div class="link">
            <a href="login.php">← กลับไปหน้าเข้าสู่ระบบ</a>
        </div>
    </div>
</body>
</html>
