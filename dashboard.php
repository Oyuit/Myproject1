<?php
include 'connect.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

// ดึงข้อมูลสรุปจากแต่ละตาราง
$totalProducts = $conn->query("SELECT COUNT(*) FROM products")->fetch_row()[0];
$totalOrders = $conn->query("SELECT COUNT(*) FROM orders")->fetch_row()[0];
$totalCustomers = $conn->query("SELECT COUNT(*) FROM customers")->fetch_row()[0];
$totalRevenue = $conn->query("SELECT SUM(total_price) FROM orders")->fetch_row()[0];
$totalViews = $conn->query("SELECT COUNT(*) FROM product_views")->fetch_row()[0];
$totalUsers = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
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
.dashboard-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-top: 30px;
  padding: 0 20px;
}

.dashboard-card {
  background: #222;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 0 10px rgba(255, 0, 0, 0.3);
  text-align: center;
  transition: transform 0.3s;
}

.dashboard-card:hover {
  transform: scale(1.05);
}

.dashboard-card h3 {
  font-size: 1.2rem;
  margin-bottom: 10px;
  color: #ffd700;
}

.dashboard-card p {
  font-size: 1.5rem;
  font-weight: bold;
  color: #fff;
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

.menu {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
  align-items: center;
}

.menu a {
  color: white;
  text-decoration: none;
  padding: 8px 12px;
  transition: background 0.3s, color 0.3s;
  border-radius: 5px;
}

.menu a:hover {
  background-color: rgba(255, 255, 255, 0.1);
  color: #ffd700;
}

/* ========== Responsive Menu ========== */
@media (max-width: 768px) {
  .navbar {
    flex-direction: column;
    align-items: flex-start;
    padding: 15px;
  }

  .menu {
    width: 100%;
    flex-direction: column;
    gap: 10px;
    margin-top: 10px;
  }

  .search-form {
    width: 100%;
    margin-top: 10px;
    justify-content: space-between;
  }

  .search-form input[type="text"] {
    width: 100%;
  }

  .menu-toggle {
    align-self: flex-end;
  }
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


    /* ...existing code... */
  .slideshow-container {
  width: 100%;
  height: auto;
}

.slides {
  height: auto;
  max-height: 480px;
  object-fit: cover;
  width: 100%;
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
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* เปลี่ยนจาก 500px เป็น 250px */
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
    
    @media (max-width: 768px) {
  .product-card img {
    height: 150px; /* หรือ auto */
  }
}

    .product-card img {
      width: 100%;
      height: 200px;
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
    <a href="shop.php">สินค้า</a>
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
  <a href="#" onclick="closeSidebar()">สินค้า</a>
</div>
  <form class="search-form" onsubmit="return false;">
    <input type="text" placeholder="ค้นหาสินค้า..." />
    <button type="submit" aria-label="Search">
      <img src="https://cdn-icons-png.flaticon.com/512/622/622669.png" alt="ค้นหา" />
    </button>
  </form>
</header>
  <section class="products">
    <h2>แดชบอร์ดผู้ดูแลระบบ</h2>
<div class="dashboard-grid">
<a href="products.php" style="text-decoration: none;">
  <div class="dashboard-card bg-primary text-white">
    <h3>สินค้า</h3>
    <p><?= $totalProducts ?> รายการ</p>
  </div>
</a>
<a href="orders.php" style="text-decoration: none;">
  <div class="dashboard-card bg-success text-white">
    <h3>ออเดอร์</h3>
    <p><?= $totalOrders ?> รายการ</p>
  </div>
</a>
<a href="customers.php" style="text-decoration: none;">
  <div class="dashboard-card bg-info text-white">
    <h3>ลูกค้า</h3>
    <p><?= $totalCustomers ?> คน</p>
  </div>
</a>
<a href="report_sales.php" style="text-decoration: none;">
  <div class="dashboard-card bg-warning text-dark">
    <h3>รวมรายได้</h3>
    <p><?= number_format($totalRevenue, 2) ?> บาท</p>
  </div>
</a>
<a href="product_views.php" style="text-decoration: none;">
  <div class="dashboard-card bg-secondary text-white">
    <h3>ยอดเข้าชมสินค้า</h3>
    <p><?= $totalViews ?> ครั้ง</p>
  </div>
</a>
<a href="users.php" style="text-decoration: none;">
  <div class="dashboard-card bg-dark text-white">
    <h3>ผู้ใช้งานระบบ</h3>
    <p><?= $totalUsers ?> คน</p>
  </div>
</a>

</div>    
  </section>

  <footer class="footer">
    <p>&copy; 2025 AKKRASIN87 | โทร: 081-234-5678 | Line: @akkrain87</p>
  </footer>

  <script>
    let slideIndex = 0;
    const slides = document.getElementsByClassName("slides");

    function showSlides() {
      for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
      }
      slideIndex++;
      if (slideIndex > slides.length) {
        slideIndex = 1;
      }
      slides[slideIndex - 1].style.display = "block";
      setTimeout(showSlides, 4000); // เปลี่ยนภาพทุก 4 วินาที
    }

    // เริ่มแสดงสไลด์
    showSlides();
  </script>
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