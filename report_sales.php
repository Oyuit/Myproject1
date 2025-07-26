<?php
include 'connect.php';
$today = date('Y-m-d');
$thisMonth = date('Y-m');
$thisYear = date('Y');

$daily = $conn->query("SELECT SUM(total_price) FROM orders WHERE DATE(order_date) = '$today'")->fetch_row()[0] ?? 0;
$monthly = $conn->query("SELECT SUM(total_price) FROM orders WHERE DATE_FORMAT(order_date, '%Y-%m') = '$thisMonth'")->fetch_row()[0] ?? 0;
$yearly = $conn->query("SELECT SUM(total_price) FROM orders WHERE YEAR(order_date) = '$thisYear'")->fetch_row()[0] ?? 0;
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>รายงานยอดขาย</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="card shadow">
      <div class="card-header bg-primary text-white">
        <h4 class="mb-0">📊 รายงานยอดขาย</h4>
      </div>
      <div class="card-body">
        <table class="table table-bordered text-center">
          <thead class="table-secondary">
            <tr>
              <th>ประเภท</th>
              <th>ยอดขาย (บาท)</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>วันนี้ (<?= $today ?>)</td>
              <td><?= number_format($daily, 2) ?></td>
            </tr>
            <tr>
              <td>เดือนนี้ (<?= $thisMonth ?>)</td>
              <td><?= number_format($monthly, 2) ?></td>
            </tr>
            <tr>
              <td>ปีนี้ (<?= $thisYear ?>)</td>
              <td><?= number_format($yearly, 2) ?></td>
            </tr>
          </tbody>
        </table>

        <form method="post" action="export_sales.php">
          <input type="hidden" name="daily" value="<?= $daily ?>">
          <input type="hidden" name="monthly" value="<?= $monthly ?>">
          <input type="hidden" name="yearly" value="<?= $yearly ?>">
          <button type="submit" class="btn btn-success">📥 ส่งออก Excel</button>
        </form>

      </div>
    </div>
  </div>
</body>
</html>

