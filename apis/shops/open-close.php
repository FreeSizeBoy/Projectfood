<?php
require_once 'database.php';
require_once 'sevice/seviceshop.php';

// รับ ID ของร้านที่ต้องการเปลี่ยนสถานะ
$id = $parameters[0];

if (!$id) {
    echo json_encode([
        'status' => false,
        'message' => 'กรุณาระบุ ID ของร้าน'
    ]);
    return;
}

// เรียกใช้ฟังก์ชัน toggleShopStatus
$response = toggleShopStatus($conn, $id);

echo json_encode($response);
