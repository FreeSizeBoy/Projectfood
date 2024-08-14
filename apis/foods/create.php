<?php
require_once 'database.php';
require_once 'sevice/food.php';
require_once 'sevice/upload.php';

// if ($_SESSION['role'] !== 'super_admin') {
//     echo json_encode([
//         'status' => false,
//         'massage' => 'ไม่สามารถสร้างร้านได้'
//     ]);
//     return;
// }

$shop_id = $_POST['shop_id'];
$menuname = $_POST['menuname'];
$price = $_POST['price'];
$stock = $_POST['stock'];
$type = $_POST['type'];

$imageUrl = uploadFile($_FILES['imageUrl'], FOOD_UPLOAD_DIR);
if (!$imageUrl['status']) {
    echo json_encode($imageUrl);
    return;
}

$imageUrl = $imageUrl['filename'];

$foods = createMenus($conn, $shop_id, $menuname , $imageUrl, $price, $stock , $type);
                    
if ($foods === null) {
    echo json_encode([
        'status' => false,
        'massage' => 'เกิดข้อผิดพลาด'
    ]);
    return;
}

echo json_encode([
    'status' => true,
    'massage' => 'สร้างอาหารสำเร็จ',
]);
