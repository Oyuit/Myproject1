<?php
session_start();

// ตัวอย่างสินค้า (จริงควรดึงจากฐานข้อมูล)
$products = [
    1 => ['name' => 'น้ำมันเครื่อง Yamalube', 'price' => 120],
    2 => ['name' => 'ผ้าเบรกหน้า', 'price' => 250],
    3 => ['name' => 'แบตเตอรี่ GS', 'price' => 850]
];

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';

    if ($name && $phone && $address) {
        $order_code = 'ORD' . rand(1000, 9999);
        $_SESSION['cart'] = [];
        $_SESSION['success'] = "สั่งซื้อสำเร็จ! หมายเลขคำสั่งซื้อ: $order_code";
        header("Location: thankyou.php");
        exit;
    } else {
        $error = "กรุณากรอกข้อมูลให้ครบถ้วน";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ยืนยันคำสั่งซื้อ | ร้านอะไหล่จักรยานยนต์</title>
  <link href="https://fonts.googleapis.com/css2?family=Sriracha&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Sriracha", cursive; }
    body { background: linear-gradient(135deg, #1a1a1a, #4d4d4d, #ff0000); color: #fff; line-height: 1.6; }
    .navbar { background: linear-gradient(135deg, #1a1a1a, #4d4d4d, #ff0000); padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
    .logo { display: flex; align-items: center; gap: 10px; }
    .logo img { height: 50px; }
    .menu { display: flex; gap: 20px; flex-wrap: wrap; align-items: center; }
    .menu a { color: white; text-decoration: none; padding: 8px 12px; border-radius: 5px; transition: background 0.3s, color 0.3s; }
    .menu a:hover { background-color: rgba(255,255,255,0.1); color: #ffd700; }
    .menu-toggle { font-size: 26px; background: none; border: none; color: white; cursor: pointer; margin-right: 10px; }
    .sidebar { height: 100%; width: 0; position: fixed; z-index: 1000; top: 0; left: 0; background-color: #111; overflow-x: hidden; transition: 0.3s; padding-top: 60px; }
    .sidebar a { padding: 12px 24px; text-decoration: none; color: #fff; display: block; transition: 0.2s; }
    .sidebar a:hover { background-color: #333; }
    .sidebar .closebtn { position: absolute; top: 10px; right: 20px; font-size: 30px; cursor: pointer; }
    .search-form { display: flex; align-items: center; margin-left: auto; background: #fff; border-radius: 20px; padding: 5px 10px; box-shadow: 0 0 5px rgba(255,255,255,1); }
    .search-form input[type="text"] { border: none; outline: none; padding: 8px; border-radius: 20px; font-size: 0.9rem; background: transparent; width: 150px; transition: width 0.3s ease; }
    .search-form input[type="text"]:focus { width: 200px; }
    .search-form button { background: none; border: none; cursor: pointer; padding: 6px; }
    .search-form button img { width: 20px; height: 20px; filter: grayscale(100%) brightness(0); transition: filter 0.3s; }
    .search-form button:hover img { filter: brightness(0.5); }
    .footer { background: linear-gradient(135deg, #1a1a1a, #4d4d4d, #ff0000); text-align: center; padding: 20px; margin-top: 60px; }
    table { width: 100%; background: #fff; color: #000; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 12px; border-bottom: 1px solid #ccc; text-align: center; }
    th { background: #ff4444; color: #fff; }
    .form-section { background: #fff; color: #000; padding: 20px; margin-top: 20px; box-shadow: 0 0 10px #ccc; }
    input, textarea { width: 100%; padding: 10px; margin-top: 5px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px; }
    .btn { background: #ff4444; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
    .btn:hover { background: #cc0000; }
    .error { color: yellow; text-align: center; margin-top: 10px; }
  </style>
</head>
<body>
  <header class="navbar">
    <button class="menu-toggle" onclick="openSidebar()">☰</button>
    <div class="logo">
      <img src="image/Logoblack.png" alt="โลโก้ร้าน" />
      <span>AKKRASIN87</span>
    </div>
    <nav class="menu"> 
      <a href="shop.php">สินค้า</a>
      <a href="#">เกี่ยวกับเรา</a>
      <a href="#">ติดต่อ</a>
      <a href="login.php">เข้าสู่ระบบ</a>
    </nav>
    <form class="search-form" onsubmit="return false;">
      <input type="text" placeholder="ค้นหาสินค้า..." />
      <button type="submit" aria-label="Search">
        <img src="https://cdn-icons-png.flaticon.com/512/622/622669.png" alt="ค้นหา" />
      </button>
    </form>
  </header>

  <div id="sidebar" class="sidebar"> 
    <span class="closebtn" onclick="closeSidebar()">×</span>
    <a href="#">หน้าแรก</a> 
    <a href="#">สินค้า</a>
    <a href="#">เกี่ยวกับเรา</a>
    <a href="#">ติดต่อ</a>
    <a href="login.php">เข้าสู่ระบบ</a>
  </div>

  <h2 style="text-align:center; margin-top:30px;">ยืนยันคำสั่งซื้อ</h2>
  <?php if (!empty($error)): ?>
    <p class="error"><?= $error ?></p>
  <?php endif; ?>

  <table>
    <tr>
      <th>สินค้า</th>
      <th>ราคา/หน่วย</th>
      <th>จำนวน</th>
      <th>รวม</th>
    </tr>
    <?php
      $total = 0;
      foreach ($_SESSION['cart'] as $id => $qty):
          $item = $products[$id];
          $subtotal = $item['price'] * $qty;
          $total += $subtotal;
    ?>
    <tr>
      <td><?= htmlspecialchars($item['name']) ?></td>
      <td><?= number_format($item['price'], 2) ?> บาท</td>
      <td><?= $qty ?></td>
      <td><?= number_format($subtotal, 2) ?> บาท</td>
    </tr>
    <?php endforeach; ?>
    <tr>
      <td colspan="3"><strong>รวมทั้งหมด</strong></td>
      <td><strong><?= number_format($total, 2) ?> บาท</strong></td>
    </tr>
  </table>

  <div class="form-section">
    <h3>ข้อมูลผู้สั่งซื้อ</h3>
    <form method="post">
      <label>ชื่อ-นามสกุล:</label>
      <input type="text" name="name" required>

      <label>เบอร์โทร:</label>
      <input type="text" name="phone" required>

      <label>ที่อยู่สำหรับจัดส่ง:</label>
      <textarea name="address" rows="4" required></textarea>

      <button type="submit" class="btn">ยืนยันคำสั่งซื้อ</button>
    </form>
  </div>

  <footer class="footer">
    <p>&copy; 2025 AKKRASIN87 | โทร: 081-234-5678 | Line: @akkrain87</p>
  </footer>

  <script>
    function openSidebar() {
      document.getElementById("sidebar").style.width = "250px";
    }
    function closeSidebar() {
      document.getElementById("sidebar").style.width = "0";
    }
  </script>
</body>
</html>
 