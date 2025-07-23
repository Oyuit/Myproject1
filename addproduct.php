<?php
include 'connect.php';

// ถ้าส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $model_id = $_POST['compatible_model_id'];

    // รูปภาพ
    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $target_dir = "image/";
    $target_path = $target_dir . basename($image_name); // ✅ ใช้ $image_name

    if (move_uploaded_file($image_tmp, $target_path)) {
        $stmt = $conn->prepare("INSERT INTO products (product_name, description, price, quantity, compatible_model_id, image_url)
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdiis", $name, $description, $price, $quantity, $model_id, $image_name);

        if ($stmt->execute()) {
            echo "<script>alert('เพิ่มสินค้าเรียบร้อย'); window.location.href='products.php';</script>";
        } else {
            echo "เกิดข้อผิดพลาด: " . $stmt->error;
        }
    } else {
        echo "ไม่สามารถอัปโหลดรูปได้";
    }
    exit;
}


// ดึงรุ่นรถมาใส่ dropdown
$model_result = $conn->query("SELECT model_id, model_name FROM motorcycle_models");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เพิ่มสินค้าใหม่</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">เพิ่มสินค้าใหม่</h2>

    <form action="products.php" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        <div class="mb-3">
            <label for="product_name" class="form-label">ชื่อสินค้า</label>
            <input type="text" name="product_name" id="product_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">คำอธิบาย</label>
            <textarea name="description" id="description" rows="3" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">ราคา (บาท)</label>
            <input type="number" name="price" id="price" step="0.01" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">ปริมาณในสต๊อก</label>
            <input type="number" name="quantity" id="quantity" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="compatible_model_id" class="form-label">เลือกรุ่นรถที่รองรับ</label>
            <select name="compatible_model_id" id="compatible_model_id" class="form-select" required>
                <option value="">-- เลือกรุ่นรถ --</option>
                <?php while ($row = $model_result->fetch_assoc()): ?>
                    <option value="<?= $row['model_id'] ?>"><?= $row['model_name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">อัปโหลดรูปสินค้า</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
        </div>

        <button type="submit" class="btn btn-success">เพิ่มสินค้า</button>
        <a href="products.php" class="btn btn-secondary">กลับหน้าสินค้า</a>
    </form>
</div>
</body>
</html>
