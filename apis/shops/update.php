<?php
require_once 'database.php';
require_once 'sevice/seviceshop.php';
require_once 'sevice/upload.php';

$id = $parameters[0];

// if ($_SESSION['role'] !== 'super_admin') {
//     echo json_encode([
//         'status' => false,
//         'massage' => 'ไม่สามารถลบร้านได้'
//     ]);
//     return;
// }

$shop = getShopById($conn, $id);

if ($shop === null) {
    echo json_encode([
        'status' => false,
        'massage' => 'ไม่พบข้อมูลร้าน'
    ]);
    return;
}



$owner_id = $_POST['owner_id'] ?? $shop['owner_id'];
$shopname = $_POST['shopname'] ?? $shop['shopname'];
$imageUrl = $_FILES['imageUrl']["name"] ?? null;

if ($imageUrl) {
    $imageUrl = uploadFile($_FILES['imageUrl'], SHOP_UPLOAD_DIR);
    if (!$imageUrl['status']) {
        echo json_encode($imageUrl);        return;
    }
    $imageUrl = $imageUrl['filename']; 
} else {
    $imageUrl = $shop['image_url'] ?? null;
}

$shop = updateShop($conn, $id, $owner_id, $shopname, $imageUrl);

if ($shop === null) {
    echo json_encode([
        'status' => false,
        'massage' => 'เกิดข้อผิดพลาด'
    ]);
    return;
}

echo json_encode([
    'status' => true,
    'massage' => 'แก้ไขรูปสำเร็จ',
]);
