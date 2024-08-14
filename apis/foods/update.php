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

$shop_id = $_POST['shop_id'] ?? $foods['shop_id'];
$menuname = $_POST['menuname'] ?? $foods['menuname'];
$price = $_POST['price'] ?? $foods['price]'];
$stock = $_POST['stock'] ?? $foods['stock'];
$type = $_POST['type'] ?? $foods['type'];
$imageUrl = $_FILES['imageUrl']["name"] ?? null;

if ($imageUrl) {
    $imageUrl = uploadFile($_FILES['imageUrl'], FOOD_UPLOAD_DIR);
    if (!$imageUrl['status']) {
        echo json_encode($imageUrl);        return;
    }
    $imageUrl = $imageUrl['filename']; 
} else {
    $imageUrl = $foods['image_url'] ?? null;
}

$foods = updateMenus($conn, $id, $shop_id, $menuname, $imageUrl, $price, $stock, $type);

if ($foods === null) {
    echo json_encode([
        'status' => false,
        'massage' => 'เกิดข้อผิดพลาด'
    ]);
    return;
}

echo json_encode([
    'status' => true,
    'massage' => 'แก้ไขอาหารสำเร็จ',
]);
