<?php
require_once 'database.php';
require_once 'sevice/orders.php';
require_once 'sevice/upload.php';

$id = $parameters[0];

// if ($_SESSION['role'] !== 'super_admin') {
//     echo json_encode([
//         'status' => false,
//         'massage' => 'ไม่สามารถลบร้านได้'
//     ]);
//     return;
// }

$orders = getOrdersById($conn, $id);

if ($orders === null) {
    echo json_encode([
        'status' => false,
        'massage' => 'ไม่พบข้อมูลอาหาร'
    ]);
    return;
}

deleteOrders($conn, $id, $orders['slip']);

echo json_encode([
    'status' => true,
    'massage' => 'ลบอาหารสำเร็จ'
]);
