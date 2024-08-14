<?php
require_once 'database.php';
require_once 'sevice/food.php';
require_once 'sevice/upload.php';

$id = $parameters[0];

// if ($_SESSION['role'] !== 'super_admin') {
//     echo json_encode([
//         'status' => false,
//         'massage' => 'ไม่สามารถลบร้านได้'
//     ]);
//     return;
// }

$foods = getMenusById($conn, $id);

if ($foods === null) {
    echo json_encode([
        'status' => false,
        'massage' => 'ไม่พบข้อมูลอาหาร'
    ]);
    return;
}

deleteMenus($conn, $id, $foods['image_url']);

echo json_encode([
    'status' => true,
    'massage' => 'ลบอาหารสำเร็จ'
]);
