<?php
require_once 'database.php';
require_once 'sevice/orders.php';
require_once 'sevice/upload.php';

$id = $parameters[0];

// ตรวจสอบการอนุญาต (ถ้าต้องการ)
if ($_SESSION['role'] !== 'super_admin' && $_SESSION['role'] !== 'admin') {
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

// ถ้าสถานะเป็น 'cancel' ให้คืน stock
if ($status === 'cancel') {
    // คืน stock
    $orderDetails = getOrderDetailsByOrderId($conn, $id);
    foreach ($orderDetails as $detail) {
        // ตรวจสอบว่าเมนูมีอยู่ในฐานข้อมูลหรือไม่ก่อนที่จะคืน stock
        if (!checkMenuExists($conn, $detail['menu_id'])) {
            continue; // ข้ามเมนูนี้ไป
        }

        if (!updateStock($conn, $detail['menu_id'], $detail['amount'])) {
            echo json_encode([
                'status' => false,
                'message' => 'เกิดข้อผิดพลาดในการคืน stock'
            ]);
            return;
        }
    }
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

// ฟังก์ชันเพื่อดึงรายละเอียดคำสั่งซื้อ
function getOrderDetailsByOrderId($conn, $orderId) {
    $sql = "SELECT * FROM orders_details WHERE orders_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    $details = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $details;
}

// ฟังก์ชันตรวจสอบว่าเมนูมีอยู่ในฐานข้อมูลหรือไม่
function checkMenuExists($conn, $menuId) {
    $sql = "SELECT id FROM menus WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $menuId);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    return $exists;
}

// ฟังก์ชันอัปเดต stock
function updateStock($conn, $menuId, $amount) {
    // เพิ่ม stock กลับ
    $sql = "UPDATE menus SET stock = stock + ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        return false;
    }

    $stmt->bind_param('ii', $amount, $menuId);
    $success = $stmt->execute();
    $stmt->close();

    return $success;
}
?>
