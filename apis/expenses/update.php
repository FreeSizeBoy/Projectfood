<?php
require_once 'database.php';
require_once 'sevice/expenses.php';
require_once 'sevice/upload.php';

$id = $parameters[0];

// if ($_SESSION['role'] !== 'super_admin') {
//     echo json_encode([
//         'status' => false,
//         'massage' => 'ไม่สามารถลบร้านได้'
//     ]);
//     return;
// }

$Expenses = getExpensesById($conn, $id);

if ($Expenses === null) {
    echo json_encode([
        'status' => false,
        'massage' => 'ไม่พบข้อมูลอาหาร'
    ]);
    return;
}

$shop_id = $_POST['shop_id'] ?? $Expenses['shop_id'];
$note = $_POST['note'] ?? $Expenses['note'];
$priceout = $_POST['priceout'] ?? $Expenses['priceout'];

$Expenses = updateExpenses($conn, $id, $shop_id, $note,$priceout);

if ($Expenses === null) {
    echo json_encode([
        'status' => false,
        'massage' => 'เกิดข้อผิดพลาด'
    ]);
    return;
}

echo json_encode([
    'status' => true,
    'massage' => 'แก้ไขข้อมูลสำเร็จ',
]);
