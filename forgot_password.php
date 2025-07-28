<?php
session_start();
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);

    // ตัวอย่างการตรวจสอบว่ามีอีเมลในระบบหรือไม่
    include 'connect.php';
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // ในระบบจริงควรส่งลิงก์รีเซ็ตรหัสผ่านทางอีเมล
        $message = "<span style='color:green;'>ระบบได้ส่งลิงก์รีเซ็ตรหัสผ่านไปยังอีเมลของคุณแล้ว</span>";
    } else {
        $message = "<span style='color:red;'>ไม่พบอีเมลในระบบ</span>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ลืมรหัสผ่าน - AKKRASIN87</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: linear-gradient(135deg, #1a1a1a, #4d4d4d, #ff0000);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: #fff;
    }

    .forgot-box {
      background: #fff;
      color: #000;
      padding: 30px;
      border-radius: 10px;
      width: 350px;
      box-shadow: 0 0 10px rgba(255, 0, 0, 0.4);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 1.6rem;
    }

    label {
      font-weight: bold;
    }

    input[type="email"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0 20px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    input[type="submit"] {
      width: 100%;
      background-color: #ff0000;
      color: #fff;
      padding: 10px;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #cc0000;
    }

    .back-link {
      text-align: center;
      margin-top: 15px;
    }

    .back-link a {
      text-decoration: none;
      color: #ff0000;
    }

    .back-link a:hover {
      text-decoration: underline;
    }

    .message {
      text-align: center;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>

  <div class="forgot-box">
    <h2>ลืมรหัสผ่าน</h2>
    <form method="post" action="">
      <div class="message"><?= $message ?></div>
      <label for="email">กรุณากรอกอีเมล:</label>
      <input type="email" name="email" required placeholder="your@email.com">
      <input type="submit" value="ส่งคำขอรีเซ็ตรหัสผ่าน">
    </form>
    <div class="back-link">
      <a href="login.php">← กลับไปเข้าสู่ระบบ</a>
    </div>
  </div>

</body>
</html>
