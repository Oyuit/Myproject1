
<?php
include 'connect.php'; // ไม่ต้องสร้าง $conn ใหม่อีก
// ดึงรายการสินค้า
$sql = "SELECT * FROM products LIMIT 8";
$products = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ร้านอะไหล่จักรยานยนต์</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sriracha&display=swap" rel="stylesheet">
  <title>Navbar พร้อมแถบข้าง</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Sriracha", cursive;
      font-weight: 400;
      font-style: normal;
}
    /* Sidebar style */
.sidebar {
  height: 100%;
  width: 0;
  position: fixed;
  z-index: 1000;
  top: 0;
  left: 0;
  background-color: #111;
  overflow-x: hidden;
  transition: 0.3s;
  padding-top: 60px;
}

.sidebar a {
  padding: 12px 24px;
  text-decoration: none;
  color: #fff;
  display: block;
  transition: 0.2s;
}

.sidebar a:hover {
  background-color: #333;
}

.sidebar .closebtn {
  position: absolute;
  top: 10px;
  right: 20px;
  font-size: 30px;
  cursor: pointer;
}

/* ปุ่ม ☰ ปรับสไตล์ให้เหมาะกับ nav */
.menu-toggle {
  font-size: 26px;
  background: none;
  border: none;
  color: white;
  cursor: pointer;
  margin-right: 10px;
}

    
    body {
      background: linear-gradient(135deg, #1a1a1a, #4d4d4d, #ff0000);

      color: #fffefeff;
      line-height: 1.6;
    }

    .navbar {
      background: linear-gradient(135deg, #1a1a1a, #4d4d4d, #ff0000);
      color: #ffffffff;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logo img {
      height: 50px;
    }

    .menu a {
      color: #ffffffff;
      text-decoration: none;
      margin-left: 20px;
      transition: color 0.3s;
    }

    .menu a:hover {
      color: #000000ff;
    }
    .search-form {
  display: flex;
  align-items: center;
  margin-left: auto;
  background: #ffffffff;
  border-radius: 20px;
  padding: 5px 10px;
  box-shadow: 0 0 5px rgba(255, 255, 255, 1);
}

.search-form input[type="text"] {
  border: none;
  outline: none;
  padding: 8px;
  border-radius: 20px;
  font-size: 0.9rem;
  background: transparent;
  width: 150px;
  transition: width 0.3s ease;
}

.search-form input[type="text"]:focus {
  width: 200px;
}

.search-form button {
  background: none;
  border: none;
  cursor: pointer;
  padding: 6px;
}

.search-form button img {
  width: 20px;
  height: 20px;
  filter: grayscale(100%) brightness(0);
  transition: filter 0.3s;
}

.search-form button:hover img {
  filter: brightness(0.5);
}

    
    .products {
      background: linear-gradient(135deg, #434343, #000000);
      padding: 40px 20px;
      text-align: center;
    }

    .products h2 {
      font-size: 1.8rem;
      margin-bottom: 30px;
    }

    .product-grid {
      
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
      gap: 20px;
      padding: 0 10px;
    }

    .product-card {
      background: linear-gradient(135deg, #1a1a1a, #4d4d4d, #ff0000);      border-radius: 10px;
      padding: 15px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 1);
      transition: transform 0.3s;
    }

    .product-card:hover {
      transform: translateY(-5px);
    }

    .product-card img {
      width: 100%;
      height: 160px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 10px;
    }

    .product-card h3 {
      font-size: 1.2rem;
      margin-bottom: 5px;
    }

    .product-card p {
      margin-bottom: 10px;
    }

    .product-card button {
      background-color: #00796b;
      color: #fff;
      border: none;
      padding: 8px 15px;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .product-card button:hover {
      background-color: #004d40;
    }

    .footer {
      background: linear-gradient(135deg, #1a1a1a, #4d4d4d, #ff0000);

      color: #fff;
      text-align: center;
      padding: 20px 20px;
      margin-top: 60px;
    }
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
    <a href="home.php">หน้าแรก</a>
    <a href="#">สินค้า</a>
    <a href="#">เกี่ยวกับเรา</a>
    <a href="#">ติดต่อ</a>
    <a href="login.php">เข้าสู่ระบบ</a>
  </nav>
  <!-- Sidebar -->
<div id="sidebar" class="sidebar">
  
  <span class="closebtn" onclick="closeSidebar()">×</span>
  <a href="#">หน้าแรก</a>
  <a href="#">สินค้า</a>
  <a href="#">เกี่ยวกับเรา</a>
  <a href="#">ติดต่อ</a>
  <a href="login.php">เข้าสู่ระบบ</a>
</div>

  <form class="search-form" onsubmit="return false;">
    <input type="text" placeholder="ค้นหาสินค้า..." />
    <button type="submit" aria-label="Search">
      <img src="https://cdn-icons-png.flaticon.com/512/622/622669.png" alt="ค้นหา" />
    </button>
  </form>
</header>

  <section class="products container">
  <h2>สินค้าในร้าน</h2>
  <div class="row g-4">
    <?php if ($products && $products->num_rows > 0): ?>
      <?php while ($row = $products->fetch_assoc()): ?>
        <div class="col-md-3 col-sm-6">
          <div class="product-card">
            <img src="image/<?= htmlspecialchars($row['image_url']) ?>" alt="รูปสินค้า">
            <h5><?= htmlspecialchars($row['product_name']) ?></h5>
            <p class="text-muted">รุ่น: <?= htmlspecialchars($row['model_name']) ?></p>
            <p class="text-danger fw-bold"><?= number_format($row['price'], 2) ?> บาท</p>
            <p class="small text-dark"><?= htmlspecialchars($row['description']) ?></p>
            <a href="#" class="btn btn-sm btn-outline-danger">ดูรายละเอียด</a>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-center text-light">ยังไม่มีสินค้าในระบบ</p>
    <?php endif; ?>
  </div>
</section>


  <footer class="footer">
    <p>&copy; 2025 AKKRASIN87 | โทร: 081-234-5678 | Line: @akkrain87</p>
  </footer>


</body>
</html>


