<?php
session_start();
include 'connect.php'; // เชื่อมฐานข้อมูล

$error = ''; // กำหนดตัวแปรแสดง error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = 'customer'");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['customer_id'] = $user['user_id'];
            $_SESSION['customer_name'] = $user['name'];

            header("Location: customer_dashboard.php");
            exit();
        } else {
            $error = "รหัสผ่านไม่ถูกต้อง";
        }
    } else {
        $error = "ไม่พบชื่อผู้ใช้ในระบบลูกค้า";
    }
}

$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เข้าสู่ระบบ</title>
  <style>
    /* CSS ตามเดิม */
    body {
      margin:  0;
      padding: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: url('hh.jpg') no-repeat center center fixed;
      background-size: cover;

    }
    .login-box {
      background: rgba(255, 255, 255, 0.9);
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 8px 32px rgba(255, 1, 1, 0.3);
      width: 100%;
      max-width: 400px;
      box-sizing: border-box;
    }
    .login-box h2 {
      text-align: center;
      color: #333;
      margin-bottom: 30px;
    }
    .login-box label {
      font-weight: bold;
      display: block;
      margin-bottom: 5px;
      color: #333;
    }
    .login-box input[type="text"],
    .login-box input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    .login-box input[type="submit"] {
      width: 100%;
      padding: 10px;
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .login-box input[type="submit"]:hover {
      background-color: #218838;
    }
    .links {
      text-align: center;
      margin-top: 15px;
    }
    .links a {
      color: #007BFF;
      text-decoration: none;
    }
    .links a:hover {
      text-decoration: underline;
    }
    @media (max-width: 480px) {
      .login-box {
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <?php if (!empty($error)) { ?>
    <p style="color:red; text-align:center;"><?php echo $error; ?></p>
  <?php } ?>
  <div class="login-box">
    <h2>เข้าสู่ระบบ</h2>
    <form method="post" action="">
      <label for="username">ชื่อผู้ใช้:</label>
      <input type="text" id="username" name="username" required>

      <label for="password">รหัสผ่าน:</label>
      <input type="password" id="password" name="password" required>

      <input type="submit" value="เข้าสู่ระบบ">
    </form>
    <div class="links">
      <a href="#">สมัครสมาชิก</a> | <a href="#">ลืมรหัสผ่าน?</a>
    </div>
  </div>
</body>
</html>
