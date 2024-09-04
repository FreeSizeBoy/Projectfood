<?php
require_once 'database.php';
require_once 'sevice/expenses.php';

// if ($_SESSION['role'] !== 'super_admin') {
//     echo json_encode([
//         'status' => false,
//         'massage' => 'ไม่สามารถสร้างร้านได้'
//     ]);
//     return;
// }

$shop_id = $_POST['shop_id'];
$note= $_POST['note'];
$priceout = $_POST['priceout'];

$Expenses = createExpenses($conn, $shop_id, $note , $priceout);
                    
if ($Expenses === null) {
    echo json_encode([
        'status' => false,
        'massage' => 'เกิดข้อผิดพลาด'
    ]);
    return;
}

echo json_encode([
    'status' => true,
    'massage' => 'สร้างรายการสำเร็จ',
]);
