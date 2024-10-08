<?php
function getMenusById($conn, $id)
{
    $sql = "SELECT * FROM menus WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        return null;
    }

    return $result->fetch_assoc();
}

function getMenus($conn, $shopId = null)
{
    // เริ่มต้นสร้างคิวรี SQL
    $sql = "SELECT * FROM menus WHERE (1 = 1)";

    // หาก $shopId ถูกระบุ ให้เพิ่มเงื่อนไขในการค้นหา
    if ($shopId !== null) {
        // ตรวจสอบว่า $shopId เป็นค่าที่ถูกต้องหรือไม่
        if (!is_numeric($shopId)) {
            throw new InvalidArgumentException('Invalid shop ID');
        }
        $sql .= " AND shop_id = ?";
    }

    // เตรียมคำสั่ง SQL
    $stmt = $conn->prepare($sql);

    // ตรวจสอบและ bind พารามิเตอร์ตามที่กำหนด
    if ($shopId !== null) {
        $stmt->bind_param('i', $shopId);
    }

    $stmt->execute();

    $result = $stmt->get_result();
    $menus = [];
    
    while ($row = $result->fetch_assoc()) {
        $menus[] = $row;
    }

    return $menus;
}



function deleteMenus($conn, $id, $imageUrl)
{
    if (file_exists(FOOD_UPLOAD_DIR . $imageUrl)) {
        unlink(FOOD_UPLOAD_DIR . $imageUrl);
    }

    $sql = "DELETE FROM menus WHERE id = $id";
    $conn->query($sql);
}

function createMenus($conn, $shop_id, $menuname, $image_url, $price, $stock, $type)
{
    $sql = "INSERT INTO menus (shop_id, menuname, image_url, price, stock, type) VALUES ('$shop_id', '$menuname', '$image_url', '$price', '$stock', '$type')";

    if ($conn->query($sql) === TRUE) {
        return getMenusById($conn, $conn->insert_id);
    }

    return null;
}

function updateMenus($conn, $id, $shop_id, $menuname, $image_url, $price, $stock, $type)
{
    $sql = "UPDATE menus SET shop_id = '$shop_id', menuname = '$menuname', image_url = '$image_url', price = '$price', stock = '$stock' , type = '$type'  WHERE id = $id";

    $conn->query($sql);

    return getMenusById($conn, $id);
}
