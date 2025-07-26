<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand_name = trim($_POST['brand_name']);

    if (empty($brand_name)) {
        echo "<script>alert('กรุณากรอกชื่อยี่ห้อ'); window.history.back();</script>";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO brands (brand_name) VALUES (?)");
    $stmt->bind_param("s", $brand_name);

    if ($stmt->execute()) {
        echo "<script>alert('เพิ่มยี่ห้อเรียบร้อย'); window.location.href='brand.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เพิ่มยี่ห้อรถจักรยานยนต์</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">เพิ่มยี่ห้อรถจักรยานยนต์</h2>
    <form method="POST" class="bg-white p-4 rounded shadow-sm">
        <div class="mb-3">
            <label for="brand_name" class="form-label">ชื่อยี่ห้อรถจักรยานยนต์</label>
            <input type="text" name="brand_name" id="brand_name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">เพิ่มยี่ห้อ</button>
        <a href="brand.php" class="btn btn-secondary">กลับหน้ารายการยี่ห้อ</a>
    </form>
</div>
</body>
</html>