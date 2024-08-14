<?php
require_once 'database.php';
require_once 'sevice/food.php';
require_once 'sevice/upload.php';

$id = $parameters[0];

$foods = getMenusById($conn, $id);

if ($foods === null) {
    echo json_encode([
        'status' => false,
        'massage' => 'ไม่พบข้อมูลอาหาร'
    ]);
    return;
}

echo json_encode([
    'status' => true,
    'data' => $foods
]);
