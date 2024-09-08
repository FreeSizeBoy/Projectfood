<?php
require_once 'database.php';
require_once 'sevice/orders.php';
require_once 'sevice/upload.php';

// ตรวจสอบข้อมูลที่ส่งมาจากฟอร์ม
$user_id = $_POST['user_id'];
$shop_id = $_POST['shop_id'];
$total_price = $_POST['total_price'];
$menu_id = json_decode($_POST['menu_id'], true);
$status = 'pending';
$slip = $_FILES['slip'] ?? null;

// ตรวจสอบการอัปโหลดไฟล์สลิป
if ($slip && $slip !== 'เงินสด') {
    $uploadResult = uploadFile($_FILES['slip'], SLIP_UPLOAD_DIR);
    if (!$uploadResult['status']) {
        echo json_encode($uploadResult);
        return;
    }
    $slip = $uploadResult['filename'];
} else {
    $slip = "เงินสด";
}

// สร้างคำสั่งซื้อใหม่
$orders = createOrdersV2($conn, $user_id, $shop_id, $menu_id, $slip);

if ($orders['status'] === false) {
    echo json_encode([
        'status' => false,
        'message' => $orders['message']
    ]);
    return;
}

echo json_encode([
    'status' => true,
    'message' => 'คำสั่งซื้อสำเร็จ',
]);