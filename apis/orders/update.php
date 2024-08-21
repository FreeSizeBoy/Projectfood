<?php
require_once 'database.php';
require_once 'sevice/orders.php';
require_once 'sevice/upload.php';

$id = $parameters[0];

// ตรวจสอบการอนุญาต (ถ้าต้องการ)
if ($_SESSION['role'] !== 'super_admin') {
    echo json_encode([
        'status' => false,
        'message' => 'ไม่สามารถเปลี่ยนสถานะคำสั่งซื้อได้'
    ]);
    return;
}

// ดึงข้อมูลคำสั่งซื้อจากฐานข้อมูล
$orders = getOrdersById($conn, $id);

if ($orders === null) {
    echo json_encode([
        'status' => false,
        'message' => 'ไม่พบข้อมูลคำสั่งซื้อ'
    ]);
    return;
}

// รับค่าที่ส่งมาจากฟอร์ม
$status = $_POST['status'] ?? $orders['status'];

// อัปเดตคำสั่งซื้อในฐานข้อมูล
$result = updateOrderStatus($conn, $id, $status);

if ($result === false) {
    echo json_encode([
        'status' => false,
        'message' => 'เกิดข้อผิดพลาดในการอัปเดตสถานะ'
    ]);
    return;
}

echo json_encode([
    'status' => true,
    'message' => 'เปลี่ยนสถานะคำสั่งซื้อสำเร็จ'
]);

// ฟังก์ชันอัปเดตสถานะคำสั่งซื้อ
function updateOrderStatus($conn, $id, $status) {
    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        return false;
    }

    $stmt->bind_param('si', $status, $id);
    $success = $stmt->execute();
    $stmt->close();

    return $success;
}
?>
