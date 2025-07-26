<?php
include 'connect.php';

// ดึงยี่ห้อรถทั้งหมดมาแสดงใน dropdown
$brands_result = $conn->query("SELECT brand_id, brand_name FROM brands");

// เมื่อมีการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model_id = $_POST['model_id'];
    $brand_id = $_POST['brand_id'];
    $model_name = $_POST['model_name'];
    $year = $_POST['year'];

    // ตรวจสอบค่าที่กรอก
    if (empty($model_id) || empty($brand_id) || empty($model_name) || empty($year)) {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบถ้วน'); window.history.back();</script>";
        exit;
    }

    // ตรวจสอบว่า brand_id มีอยู่จริงหรือไม่
    $check_brand = $conn->prepare("SELECT brand_id FROM brands WHERE brand_id = ?");
    $check_brand->bind_param("i", $brand_id);
    $check_brand->execute();
    $check_brand->store_result();

    if ($check_brand->num_rows === 0) {
        echo "<script>alert('Brand ID นี้ไม่มีในระบบ กรุณาเลือกยี่ห้อรถที่ถูกต้อง'); window.history.back();</script>";
        exit;
    }
    $check_brand->close();

    // บันทึกลงฐานข้อมูล
    $sql = "INSERT INTO motorcycle_models (model_id, brand_id, model_name, year) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisi", $model_id, $brand_id, $model_name, $year);

    if ($stmt->execute()) {
        echo "<script>alert('เพิ่มรุ่นรถเรียบร้อย'); window.location.href='addmodel.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เพิ่มรุ่นรถจักรยานยนต์</title>
    <style>
        body { font-family: Arial; background: #f9f9f9; padding: 20px; }
        form { background: #fff; padding: 20px; border-radius: 8px; max-width: 400px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input[type="text"], input[type="number"], select {
            width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px;
        }
        button {
            padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<h2 style="text-align: center;">เพิ่มรุ่นรถจักรยานยนต์</h2>

<form method="POST">
    <label>หมายเลขรุ่นรถจักรยานยนต์ (Model ID)</label>
    <input type="number" name="model_id" required>

    <label>ยี่ห้อรถจักรยานยนต์ (Brand)</label>
    <select name="brand_id" required>
        <option value="">-- เลือกยี่ห้อ --</option>
        <?php while ($row = $brands_result->fetch_assoc()): ?>
            <option value="<?= $row['brand_id'] ?>"><?= $row['brand_name'] ?></option>
        <?php endwhile; ?>
    </select>

    <label>ชื่อรุ่นรถจักรยานยนต์ (Model Name)</label>
    <input type="text" name="model_name" required>

    <label>ปี (Year)</label>
    <input type="number" name="year" required>

    <button type="submit">เพิ่มรุ่นรถ</button>
</form>

</body>
</html>