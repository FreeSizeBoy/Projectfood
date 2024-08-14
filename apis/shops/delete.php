<?php
require_once 'database.php';
require_once 'sevice/seviceshop.php';
require_once 'sevice/upload.php';

$id = $parameters[0];

if ($_SESSION['role'] !== 'super_admin') {
    echo json_encode([
        'status' => false,
        'massage' => 'ไม่สามารถลบร้านได้'
    ]);
    return;
}

$shop = getShopById($conn, $id);

if ($shop === null) {
    echo json_encode([
        'status' => false,
        'massage' => 'ไม่พบข้อมูลร้าน'
    ]);
    return;
}

deleteShop($conn, $id, $shop['imageUrl']);

echo json_encode([
    'status' => true,
    'massage' => 'ลบร้านสำเร็จ'
]);
