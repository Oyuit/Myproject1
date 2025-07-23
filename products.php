<?php
include 'connect.php';

// ==== เพิ่มสินค้าเมื่อส่งฟอร์ม ====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $model_id = $_POST['compatible_model_id'];

    // จัดการรูปภาพ
    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $target_dir = "image/";
    $target_path = $target_dir . basename($image_name);

    if (move_uploaded_file($image_tmp, $target_path)) {
        $stmt = $conn->prepare("INSERT INTO products (product_name, description, price, quantity, compatible_model_id, image_url)
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdiis", $name, $description, $price, $quantity, $model_id, $image_name);

        if ($stmt->execute()) {
            echo "<script>alert('เพิ่มสินค้าเรียบร้อย'); window.location.href='products.php';</script>";
            exit;
        } else {
            echo "เกิดข้อผิดพลาด: " . $stmt->error;
        }
    } else {
        echo "อัปโหลดรูปไม่สำเร็จ";
    }
}

// ==== ดึงข้อมูลสินค้า ====
$sql = "SELECT p.product_id, p.product_name, p.price, p.description, p.quantity, p.image_url, m.model_name
        FROM products p
        LEFT JOIN motorcycle_models m ON p.compatible_model_id = m.model_id
        ORDER BY p.product_id DESC";
$result = $conn->query($sql);

// ==== ดึงข้อมูลรุ่นรถ ====
$model_result = $conn->query("SELECT model_id, model_name FROM motorcycle_models");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ระบบจัดการสินค้า</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">รายการสินค้า</h2>

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
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
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
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">ไม่มีข้อมูลสินค้า</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <hr class="my-5">

    <h4>เพิ่มสินค้าใหม่</h4>
    <form method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm mt-3">
        <div class="mb-3">
            <label class="form-label">ชื่อสินค้า</label>
            <input type="text" name="product_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">คำอธิบาย</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">ราคา</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">จำนวน</label>
            <input type="number" name="quantity" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">เลือกรุ่นรถ</label>
            <select name="compatible_model_id" class="form-select" required>
                <option value="">-- เลือกรุ่น --</option>
                <?php while ($model = $model_result->fetch_assoc()): ?>
                    <option value="<?= $model['model_id'] ?>"><?= $model['model_name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">อัปโหลดรูปสินค้า</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>
        </div>

        <button type="submit" class="btn btn-success">เพิ่มสินค้า</button>
    </form>
</div>
</body>
</html>
