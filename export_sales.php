<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="sales_report.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ประเภท', 'ยอดขาย (บาท)']);

    fputcsv($output, ['วันนี้', $_POST['daily']]);
    fputcsv($output, ['เดือนนี้', $_POST['monthly']]);
    fputcsv($output, ['ปีนี้', $_POST['yearly']]);

    fclose($output);
    exit;
}
