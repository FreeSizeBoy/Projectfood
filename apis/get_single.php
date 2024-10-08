<?php
require_once 'database.php';

$id = $parameters[0];

function getUserById($conn, $id)
{
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        return null;
    }

    return $result->fetch_assoc();
}

function getUserOrders($conn, $userId)
{
    $sql = "SELECT id AS order_id, total_price, status, createdAt FROM orders WHERE user_id = $userId";
    $result = $conn->query($sql);

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    return $orders;
}

function getOrderDetails($conn, $orderId)
{
    $sql = "
        SELECT 
            od.menu_id, 
            od.price AS detail_price, 
            od.amount, 
            od.note, 
            od.extra,
            m.menuname, 
            m.image_url AS menu_image,
            m.price AS menu_price
        FROM 
            orders_details od
        LEFT JOIN 
            menus m ON od.menu_id = m.id
        WHERE 
            od.orders_id = $orderId
    ";
    
    $result = $conn->query($sql);
    $details = [];

    while ($row = $result->fetch_assoc()) {
        $details[] = $row;
    }

    return $details;
}

$user = getUserById($conn, $id);

if ($user === null) {
    echo json_encode([
        'status' => false,
        'message' => 'ไม่พบข้อมูลผู้ใช้'
    ]);
    return;
}

$userOrders = getUserOrders($conn, $id);

foreach ($userOrders as &$order) {
    $order['details'] = getOrderDetails($conn, $order['order_id']);
}

echo json_encode([
    'status' => true,
    'data' => [
        'user' => $user,
        'orders' => $userOrders
    ]
]);
