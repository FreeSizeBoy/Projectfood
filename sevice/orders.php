<?php

function getUserById($conn, $id)
{
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        return null;
    }

    return $result->fetch_assoc();
}

function getOrdersById($conn, $id)
{
    $sql = "SELECT * FROM orders WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        return null;
    }

    return $result->fetch_assoc();
}

function getOrders($conn, $page = 1, $limit = 10, $filter = '' ,$search = '')
{
    $offset = ($page - 1) * $limit;

    // SQL query to join orders with users, order details, and menus
    $sql = "
        SELECT 
            orders.*, 
            users.username,
            users.nickname,
            users.room,
            users.email,
            orders_details.menu_id,
            orders_details.price AS detail_price,
            orders_details.amount AS detail_amount,
            orders_details.note,
            orders_details.extra,
            menus.menuname AS menu_name,
            menus.image_url AS menu_image
        FROM orders
        JOIN users ON orders.user_id = users.id
        LEFT JOIN orders_details ON orders.id = orders_details.orders_id
        LEFT JOIN menus ON orders_details.menu_id = menus.id
        WHERE users.username LIKE '%$search%' ";

    if(!empty($filter)){
        $sql = $sql . " AND orders.shop_id = '$filter' ";
    }

    $sql = $sql . "LIMIT $limit OFFSET $offset ";
    
    $result = $conn->query($sql);

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        // Organize order details under their respective orders
        $orderId = $row['id'];
        if (!isset($orders[$orderId])) {
            $orders[$orderId] = [
                'id' => $row['id'],
                'shop_id' => $row['shop_id'],
                'user_id' => $row['user_id'],
                'total_price' => $row['total_price'],
                'status' => $row['status'],
                'slip' => $row['slip'],
                'createdAt' => $row['createdAt'],
                'username' => $row['username'],
                'nickname' => $row['nickname'],
                'room' => $row['room'],
                'email' => $row['email'],
                'details' => []
            ];
        }
        
        // Add order details including menu information
        if ($row['menu_id']) {
            $orders[$orderId]['details'][] = [
                'menu_id' => $row['menu_id'],
                'menu_name' => $row['menu_name'],
                'menu_image' => $row['menu_image'],
                'price' => $row['detail_price'],
                'amount' => $row['detail_amount'],
                'note' => $row['note'],
                'extra' => $row['extra']
            ];
        }
    }

    return array_values($orders); // Return as a list of orders
}



function deleteOrders($conn, $id, $imageUrl)
{
    if (file_exists(SLIP_UPLOAD_DIR . $imageUrl)) {
        unlink(SLIP_UPLOAD_DIR . $imageUrl);
    }

    $sql = "DELETE FROM orders WHERE id = $id";
    $conn->query($sql);
}

function createOrders($conn, $user_id, $menu_id, $price, $status, $slip)
{
    $sql = "INSERT INTO orders (user_id, menu_id, price, status, slip) VALUES ('$user_id', '$menu_id', '$price', '$status', '$slip')";

    if ($conn->query($sql) === TRUE) {
        return getOrdersById($conn, $conn->insert_id);
    }

    return null;
}

function createOrdersV2($conn, $user_id, $shop_id, $menus, $slips)
{
    // เริ่มต้น transaction
    $conn->begin_transaction();

    try {
        // ตรวจสอบสถานะของร้านค้า
        $sql = "SELECT status FROM shops WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $shop_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $shopData = $result->fetch_assoc();

        if ($shopData['status'] === 'ปิด') {
            throw new Exception("ไม่สามารถสั่งซื้อได้ ร้านค้าปิดอยู่");
        }

        // คำนวณราคาทั้งหมด
        $total_price = 0;
        $order_id = null;

        // แทรกคำสั่งซื้อ
        $status = 'pending'; // หรือสถานะที่คุณต้องการ
        $sql = "INSERT INTO orders (shop_id, user_id, total_price, status, slip) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisss", $shop_id, $user_id, $total_price, $status, $slips);
        $stmt->execute();
        
        // ดึง ID ของคำสั่งซื้อใหม่
        $order_id = $conn->insert_id;

        // แทรกรายละเอียดการสั่งซื้อ
        foreach ($menus as $menu) {
            // ดึงราคาเมนูจากฐานข้อมูล
            $sql = "SELECT price, stock, menuname FROM menus WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $menu['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $menuData = $result->fetch_assoc();
            $price = $menuData['price'];
            $stock = $menuData['stock'];

            // ตรวจสอบสต็อกเพียงพอหรือไม่
            if ($stock < $menu['quantity']) {
                throw new Exception("สต็อกไม่เพียงพอสำหรับเมนู " . $menuData['menuname']);
            }

            // คำนวณราคาสำหรับรายการนี้
            $item_total_price = ($price + ($menu['extra'] ?? 0)) * $menu['quantity'];
            $total_price += $item_total_price;

            // แทรกรายละเอียดการสั่งซื้อ
            $extra = isset($menu['extra']) ? 'พิเศษ' : 'ไม่พิเศษ'; // ใช้ค่าเริ่มต้นถ้าไม่มีค่า extra
            $sql = "INSERT INTO orders_details (orders_id, menu_id, price, amount, note, extra) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiiiss", $order_id, $menu['id'], $price, $menu['quantity'], $menu['extraText'], $extra);
            $stmt->execute();

            // ตัดสต็อกเมนู
            $new_stock = $stock - $menu['quantity'];
            $sql = "UPDATE menus SET stock = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $new_stock, $menu['id']);
            $stmt->execute();
        }

        // อัปเดตราคาทั้งหมดในตารางคำสั่งซื้อ
        $sql = "UPDATE orders SET total_price = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $total_price, $order_id);
        $stmt->execute();

        // Commit transaction
        $conn->commit();

        return [
            'status' => true,
            'data' => getOrdersById($conn, $order_id)
        ];
    } catch (Exception $e) {
        // Rollback transaction หากเกิดข้อผิดพลาด
        $conn->rollback();
        return [
            'status' => false,
            'message' => $e->getMessage()
        ];
    }
}


function updateOrders($conn, $id, $user_id, $menu_id, $price, $status, $slip)
{
    $sql = "UPDATE orders SET user_id = '$user_id', menu_id = '$menu_id', price = '$price', status = '$status', slip = '$slip' WHERE id = $id";

    $conn->query($sql);

    return getOrdersById($conn, $id);
}
