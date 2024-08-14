<?php
require_once 'database.php';
require_once 'sevice/orders.php';
require_once 'sevice/upload.php';

$id = $parameters[0];

$orders = getOrdersById($conn, $id);

if ($orders === null) {
    echo json_encode([
        'status' => false,
        'massage' => 'ไม่พบข้อมูลอาหาร'
    ]);
    return;
}

echo json_encode([
    'status' => true,
    'data' => $orders
]);
