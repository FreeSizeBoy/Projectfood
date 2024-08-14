<?php
require_once 'database.php';
require_once 'sevice/seviceshop.php';
require_once 'sevice/upload.php';

$id = $parameters[0];

$shop = getShopById($conn, $id);

if ($shop === null) {
    echo json_encode([
        'status' => false,
        'massage' => 'ไม่พบข้อมูลร้านอาหาร'
    ]);
    return;
}

echo json_encode([
    'status' => true,
    'data' => $shop
]);
