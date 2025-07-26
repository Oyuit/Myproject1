<?php
include 'connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ลบรูปภาพก่อน (หากมี)
    $img = $conn->query("SELECT image_url FROM products WHERE product_id = $id")->fetch_assoc();
    if ($img && file_exists("image/" . $img['image_url'])) {
        unlink("image/" . $img['image_url']);
    }

    // ลบข้อมูลจากฐานข้อมูล
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('ลบสินค้าสำเร็จ'); window.location.href='products.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาดในการลบ: " . $stmt->error;
    }
}
?>
