<?php
include 'connect.php';

$sql = "SELECT brand_id, brand_name FROM brands ORDER BY brand_id ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายการยี่ห้อรถจักรยานยนต์</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">รายการยี่ห้อรถจักรยานยนต์</h2>
    <a href="addbrand.php" class="btn btn-success mb-3">เพิ่มยี่ห้อใหม่</a>
    <table class="table table-bordered table-striped bg-white">
        <thead>
            <tr>
                <th>รหัสยี่ห้อ</th>
                <th>ชื่อยี่ห้อ</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['brand_id'] ?></td>
                        <td><?= htmlspecialchars($row['brand_name']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="2" class="text-center">ไม่มีข้อมูลยี่ห้อ</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="products.php" class="btn btn-secondary">กลับหน้าแรก</a>
</div>
</body>
</html>