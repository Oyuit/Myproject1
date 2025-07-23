<?php
$host = 'localhost';      // ชื่อโฮสต์ของฐานข้อมูล เช่น localhost
$user = 'root';           // ชื่อผู้ใช้ของฐานข้อมูล
$password = '';           // รหัสผ่านของฐานข้อมูล
$dbname = 'akkrasin'; // ชื่อฐานข้อมูลที่ต้องการเชื่อมต่อ
// สร้างการเชื่อมต่อ
$conn = new mysqli($host, $user, $password, $dbname);
// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die(" การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}
$debug = false;

if ($debug) {
    echo "เชื่อมต่อฐานข้อมูลสำเร็จ!";
}

?>