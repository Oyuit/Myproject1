<?php
include 'connect.php';

// ดึงข้อมูลเดิม
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $product = $conn->query("SELECT * FROM products WHERE product_id = $id")->fetch_assoc();
    $models = $conn->query("SELECT * FROM motorcycle_models");

    if (!$product) {
        echo "ไม่พบสินค้า"; exit;
    }
}

// อัปเดตข้อมูลเมื่อ POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['product_id'];
    $name = $_POST['product_name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $qty = $_POST['quantity'];
    $model_id = $_POST['compatible_model_id'];

    $stmt = $conn->prepare("UPDATE products SET product_name=?, description=?, price=?, quantity=?, compatible_model_id=? WHERE product_id=?");
    $stmt->bind_param("ssdiii", $name, $desc, $price, $qty, $model_id, $id);

    if ($stmt->execute()) {
        echo "<script>alert('แก้ไขสินค้าเรียบร้อย'); window.location.href='products.php';</script>";
    } else {
        echo "ผิดพลาด: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขสินค้า</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h4>แก้ไขสินค้า</h4>
    <form method="POST" class="bg-white p-4 rounded shadow-sm mt-3">
        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">

        <div class="mb-3">
            <label class="form-label">ชื่อสินค้า</label>
            <input type="text" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">คำอธิบาย</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($product['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">ราคา</label>
            <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">จำนวน</label>
            <input type="number" name="quantity" value="<?= $product['quantity'] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">เลือกรุ่นรถ</label>
            <select name="compatible_model_id" class="form-select" required>
                <?php while($model = $models->fetch_assoc()): ?>
                    <option value="<?= $model['model_id'] ?>" <?= $product['compatible_model_id'] == $model['model_id'] ? 'selected' : '' ?>>
                        <?= $model['model_name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-success">บันทึก</button>
        <a href="products.php" class="btn btn-secondary">ยกเลิก</a>
    </form>
</div>
</body>
</html>
