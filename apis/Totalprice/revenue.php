<?php

header('Content-Type: application/json');
date_default_timezone_set('Asia/Bangkok'); // Set timezone to Bangkok

require_once "database.php";

// รับค่าตัวแปรจาก URL
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$period = isset($_GET['period']) ? $_GET['period'] : 'month'; // ค่าเริ่มต้นเป็น 'month'
$shop_id = isset($_GET['shop_id']) ? intval($_GET['shop_id']) : 0; // ค่าเริ่มต้นเป็น 0 หมายถึงไม่มีการกรองโดย shop_id

$periodQuery = '';
$groupBy = '';
$labels = [];
$incomeData = [];
$expenseData = [];
$between = '=';

// กำหนด Query และ Labels ตามช่วงเวลา
if ($period == 'day') {
    // Fetch the last 7 days including today
    $periodQuery = "DATE(createdAt)";
    $groupBy = "GROUP BY DATE(createdAt)";
    for ($i = 7; $i >= 0; $i--) {
        $labels[] = date('d/m/Y', strtotime("-$i days"));
    }
} elseif ($period == 'month') {
    // Fetch data for each month of the current year
    $periodQuery = "MONTH(createdAt)";
    $groupBy = "GROUP BY MONTH(createdAt)";
    $labels = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
} elseif ($period == 'year') {
    // Fetch data for the current year and the past 3 years
    $periodQuery = "YEAR(createdAt)";
    $groupBy = "GROUP BY YEAR(createdAt)";
    $currentYear = date('Y');
    $labels = [$currentYear - 2, $currentYear - 1, $currentYear];
    $startyear = $year - 2;
    $between = "BETWEEN $startyear AND";
}

// Initialize income and expense arrays with 0 values for each label
$incomeData = array_fill(0, count($labels), 0);
$expenseData = array_fill(0, count($labels), 0);

// Query สำหรับดึงข้อมูลรายรับ
$sql = "
    SELECT 
        $periodQuery AS period,
        SUM(total_price) AS total_income
    FROM orders
    WHERE YEAR(createdAt) $between ?
    AND shop_id = ?
    AND status = 'complete'
    $groupBy
    ORDER BY $periodQuery
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $year, $shop_id);
$stmt->execute();
$result_income = $stmt->get_result();

while ($row = $result_income->fetch_assoc()) {
    if ($period == 'day') {
        // Format date to d/m/Y to match labels
        $periodIndex = array_search(date('d/m/Y', strtotime($row['period'])), $labels);
    } else if ($period == 'month'){
        $periodIndex = $row['period'] - 1; // For month, adjust to 0-based index
    } else if ($period == 'year') {
        $periodIndex = array_search($row['period'], $labels);
    }
    
    if ($periodIndex !== false) {
        $incomeData[$periodIndex] = $row['total_income'];
    }
}

// Query สำหรับดึงข้อมูลรายจ่าย
$sql = "
    SELECT 
        $periodQuery AS period,
        SUM(priceout) AS total_expenses
    FROM expenses
    WHERE YEAR(createdAt) $between ?
    AND shop_id = ?
    $groupBy
    ORDER BY $periodQuery
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $year, $shop_id);
$stmt->execute();
$result_expenses = $stmt->get_result();

while ($row = $result_expenses->fetch_assoc()) {
    if ($period == 'day') {
        // Format date to d/m/Y to match labels
        $periodIndex = array_search(date('d/m/Y', strtotime($row['period'])), $labels);
    } else if ($period == 'month'){
        $periodIndex = $row['period'] - 1; // For month, adjust to 0-based index
    } else if ($period == 'year') {
        $periodIndex = array_search($row['period'], $labels);
    }
    
    if ($periodIndex !== false) {
        $expenseData[$periodIndex] = $row['total_expenses'];
    }
}

// เตรียมข้อมูลสำหรับส่งกลับ
$response = [
    'labels' => $labels,
    'income' => array_values($incomeData),
    'expenses' => array_values($expenseData)
];

echo json_encode($response);

?>