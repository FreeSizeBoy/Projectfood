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

$user_id = $_POST['owner_id'] ?? $orders['user_id'];
$menu_id = $_POST['menuname'] ?? $orders['menu_id'];
$price = $_POST['price'] ?? $orders['price]'];
$status = $_POST['stock'] ?? $orders['status'];
$slip = $_POST['type'] ?? $orders['slip'];
$users_name = $_FILES['uploadImage']["users_name"] ?? null;

if ($imageUrl) {
    $imageUrl = uploadFile($_FILES['uploadImage'], SLIP_UPLOAD_DIR);
    if (!$imageUrl['status']) {
        echo json_encode($imageUrl);        return;
    }
    $imageUrl = $imageUrl['filename']; 
} else {
    $imageUrl = $orders['img_url'] ?? null;
}

$orders = updateOrders($conn, $id, $user_id, $menu_id, $price, $status, $slip, $users_name);

if ($orders === null) {
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
