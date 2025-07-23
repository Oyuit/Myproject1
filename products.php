<?php
include 'connect.php';

// ==== ดึงข้อมูลสินค้า ====
$sql = "SELECT p.product_id, p.product_name, p.price, p.description, p.quantity, p.image_url, m.model_name
        FROM products p
        LEFT JOIN motorcycle_models m ON p.compatible_model_id = m.model_id
        ORDER BY p.product_id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายการสินค้า</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>รายการสินค้า</h2>
        <a href="addproduct.php" class="btn btn-primary">+ เพิ่มสินค้า</a>
    </div>

    <table class="table table-bordered table-striped bg-white">
        <thead>
            <tr>
                <th>รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>ราคา</th>
                <th>คำอธิบาย</th>
                <th>จำนวน</th>
                <th>รุ่นรถ</th>
                <th>รูปภาพ</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['product_id'] ?></td>
    <td><?= htmlspecialchars($row['product_name']) ?></td>
    <td><?= number_format($row['price'], 2) ?></td>
    <td><?= htmlspecialchars($row['description']) ?></td>
    <td><?= $row['quantity'] ?></td>
    <td><?= htmlspecialchars($row['model_name']) ?></td>
    <td>
        <?php if (!empty($row['image_url'])): ?>
            <img src="image/<?= htmlspecialchars($row['image_url']) ?>" width="80">
        <?php else: ?>
            ไม่มีรูป
        <?php endif; ?>
    </td>
    <td>
        <a href="editproduct.php?id=<?= $row['product_id'] ?>" class="btn btn-sm btn-warning">แก้ไข</a>
        <a href="deleteproduct.php?id=<?= $row['product_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('ยืนยันการลบสินค้านี้?')">ลบ</a>
    </td>
</tr>
<?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
