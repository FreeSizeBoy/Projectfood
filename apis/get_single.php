<?php

require_once 'database.php';

$id = $parameters[0];

function getUserByIdWithOrders($conn, $id)
{
    // ดึงข้อมูลผู้ใช้พร้อมกับรายการสั่งซื้อและรายละเอียดอาหารที่เกี่ยวข้อง
    $sql = "
        SELECT 
            u.*, 
            o.id AS order_id, 
            o.total_price, 
            o.status, 
            o.createdAt,
            od.menu_id, 
            od.price AS detail_price, 
            od.amount, 
            od.note, 
            od.extra,
            m.menuname, 
            m.image_url AS menu_image,
            m.price AS menu_price
        FROM 
            users u
        LEFT JOIN 
            orders o ON u.id = o.user_id
        LEFT JOIN 
            orders_details od ON o.id = od.orders_id
        LEFT JOIN 
            menus m ON od.menu_id = m.id
        WHERE 
            u.id = $id
    ";

    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        return null;
    }

    $userOrders = [];
    while ($row = $result->fetch_assoc()) {
        if (!isset($userOrders['user'])) {
            // ดึงข้อมูลผู้ใช้ครั้งแรก
            $userOrders['user'] = [
                'id' => $row['id'],
                'email' => $row['email'],
                'role' => $row['role'],
                'fname' => $row['fname'],
                'lname' => $row['lname'],
                'room' => $row['room'],
                'student_id' => $row['student_id'],
                'gender' => $row['gender'],
                'tel' => $row['tel'],
                'img_url' => $row['img_url'],
                'username' => $row['username'],
                'nickname' => $row['nickname'],
            ];
        }

        // เพิ่มข้อมูลรายการสั่งซื้อของผู้ใช้
        if (!isset($userOrders['orders'][$row['order_id']])) {
            $userOrders['orders'][$row['order_id']] = [
                'order_id' => $row['order_id'],
                'total_price' => $row['total_price'],
                'status' => $row['status'],
                'createdAt' => $row['createdAt'],
                'details' => []
            ];
        }

        // เพิ่มรายละเอียดของรายการสั่งซื้อ
        if ($row['menu_id']) {
            $userOrders['orders'][$row['order_id']]['details'][] = [
                'menu_id' => $row['menu_id'],
                'menuname' => $row['menuname'],
                'menu_image' => $row['menu_image'],
                'detail_price' => $row['detail_price'],
                'amount' => $row['amount'],
                'note' => $row['note'],
                'extra' => $row['extra'],
            ];
        }
    }

    return $userOrders;
}

$userOrders = getUserByIdWithOrders($conn, $id);

if ($userOrders === null) {
    echo json_encode([
        'status' => false,
        'message' => 'ไม่พบข้อมูลผู้ใช้'
    ]);
    return;
}

echo json_encode([
    'status' => true,
    'data' => $userOrders
]);

