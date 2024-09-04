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
        'massage' => 'ไม่พบข้อมูลข้อมูล'
    ]);
    return;
}

deleteExpenses($conn, $id);

echo json_encode([
    'status' => true,
    'massage' => 'ลบข้อมูลสำเร็จ'
]);
