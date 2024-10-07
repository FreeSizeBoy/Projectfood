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

function getOrders($conn, $page = 1, $limit = 10, $filter = '')
{
    $offset = ($page - 1) * $limit;

    // Step 1: Get the main orders
    $sqlOrders = "
        SELECT 
            orders.id,
            orders.shop_id,
            orders.user_id,
            orders.total_price,
            orders.status,
            orders.slip,
            orders.createdAt,
            users.username,
            users.nickname,
            users.room,
            users.email
        FROM orders
        JOIN users ON orders.user_id = users.id";
    
    // Add filter condition for orders
    if (!empty($filter)) {
        $sqlOrders .= " WHERE orders.shop_id = '$filter'";
    }
    
    $sqlOrders .= " LIMIT $limit OFFSET $offset";

    $resultOrders = $conn->query($sqlOrders);
    $orders = [];
    
    while ($row = $resultOrders->fetch_assoc()) {
        // Store main order details
        $orderId = $row['id'];
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

    // Step 2: Get order details
    $orderIds = implode(',', array_keys($orders));
    
    if (!empty($orderIds)) {
        $sqlDetails = "
            SELECT 
                orders_details.orders_id,
                orders_details.menu_id,
                orders_details.price AS detail_price,
                orders_details.amount AS detail_amount,
                orders_details.note,
                orders_details.extra,
                menus.menuname AS menu_name,
                menus.image_url AS menu_image
            FROM orders_details
            LEFT JOIN menus ON orders_details.menu_id = menus.id
            WHERE orders_details.orders_id IN ($orderIds)";
        
        $resultDetails = $conn->query($sqlDetails);
        
        while ($row = $resultDetails->fetch_assoc()) {
            $orderId = $row['orders_id'];
            // Add order details including menu information
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
// โค้ดนี้เป็นฟังก์ชัน PHP ที่ชื่อว่า createOrdersV2 ซึ่งใช้ในการสร้างคำสั่งซื้อใหม่พร้อมรายละเอียดเมนูอาหารที่สั่ง โดยทำงานภายใต้การจัดการธุรกรรม (transaction) ซึ่งจะช่วยให้ข้อมูลไม่เสียหายถ้ามีข้อผิดพลาดระหว่างการดำเนินการ โดยเราสามารถอธิบายขั้นตอนแบบกันเองได้ว่า:

// เริ่มต้นธุรกรรม (Transaction):

// ฟังก์ชันจะเริ่มต้นด้วยการเปิดการทำธุรกรรม ($conn->begin_transaction()) เพื่อให้มั่นใจว่าการทำงานหลายขั้นตอน (การสั่งซื้อและตัดสต็อก) จะถูกบันทึกหรือยกเลิกทั้งชุดหากเกิดข้อผิดพลาด
// ตรวจสอบสถานะร้าน:

// ฟังก์ชันจะตรวจสอบสถานะของร้านค้าก่อน ถ้าร้านถูกปิด (status === 'ปิด') จะไม่อนุญาตให้สั่งซื้อและจะโยนข้อผิดพลาดออกมา
// คำนวณราคาทั้งหมด:

// ฟังก์ชันจะเริ่มต้นคำนวณราคาทั้งหมดเป็นศูนย์ ($total_price = 0) และเตรียมสร้างคำสั่งซื้อใหม่
// สถานะเริ่มต้นของคำสั่งซื้อถูกตั้งเป็น 'pending' และมีการบันทึกข้อมูลคำสั่งซื้อไปยังตาราง orders
// แทรกรายละเอียดการสั่งซื้อ:

// ฟังก์ชันจะวนลูปผ่านรายการเมนู ($menus) ที่สั่ง โดยสำหรับแต่ละเมนู:
// ตรวจสอบสต็อกว่าพอสำหรับจำนวนที่สั่งหรือไม่ ถ้าไม่พอก็จะโยนข้อผิดพลาดออกมา
// คำนวณราคาของเมนูตามจำนวนที่สั่งและตัวเลือกเพิ่มเติม (extra) หากมี
// บันทึกรายละเอียดของแต่ละเมนูลงในตาราง orders_details
// อัปเดตจำนวนสต็อกในตาราง menus หลังจากสั่งซื้อ
// อัปเดตราคาทั้งหมดในคำสั่งซื้อ:

// หลังจากคำนวณราคาทั้งหมดจากรายการเมนูแล้ว ฟังก์ชันจะอัปเดตราคานี้ในตาราง orders
// Commit หรือ Rollback:

// ถ้าทุกอย่างทำงานได้สำเร็จ ฟังก์ชันจะ commit การทำธุรกรรมเพื่อบันทึกข้อมูลทั้งหมด
// ถ้ามีข้อผิดพลาดเกิดขึ้นในกระบวนการใดๆ ฟังก์ชันจะ rollback การทำธุรกรรมเพื่อยกเลิกการเปลี่ยนแปลงทั้งหมด
// ส่งผลลัพธ์:

// ถ้าทำงานสำเร็จ ฟังก์ชันจะส่งผลลัพธ์เป็นสถานะ true พร้อมกับข้อมูลคำสั่งซื้อที่เพิ่งสร้าง
// ถ้าเกิดข้อผิดพลาด จะส่งสถานะ false พร้อมข้อความข้อผิดพลาด
// สรุปง่ายๆ ก็คือ ฟังก์ชันนี้ช่วยให้เราสร้างคำสั่งซื้อใหม่ที่ประกอบด้วยรายการเมนูหลายรายการ และจัดการการลดสต็อกพร้อมกันด้วย ถ้ามีข้อผิดพลาดเกิดขึ้นทุกการเปลี่ยนแปลงจะถูกยกเลิกเพื่อความปลอดภัยของข้อมูล!


function updateOrders($conn, $id, $user_id, $menu_id, $price, $status, $slip)
{
    $sql = "UPDATE orders SET user_id = '$user_id', menu_id = '$menu_id', price = '$price', status = '$status', slip = '$slip' WHERE id = $id";

    $conn->query($sql);

    return getOrdersById($conn, $id);
}
