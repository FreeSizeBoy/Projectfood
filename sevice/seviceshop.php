<?php
function getShopById($conn, $id)
{
    $sql = "SELECT * FROM shops WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        return null;
    }

    return $result->fetch_assoc();
}

function getShops($conn, $page = 1, $limit = 10, $search = '')
{
    $offset = ($page - 1) * $limit;

    // สร้างคิวรี SQL สำหรับดึงข้อมูลร้านค้าและเมนู
    $sql = "
        SELECT 
            shops.*, 
            users.username,
            menus.id AS menu_id,
            menus.menuname,
            menus.image_url AS menu_url,
            menus.price,
            menus.stock,
            menus.type
        FROM shops
        JOIN users ON shops.owner_id = users.id
        LEFT JOIN menus ON shops.id = menus.shop_id
        WHERE shops.shopname LIKE ? OR menus.menuname LIKE ?
        LIMIT ? OFFSET ?
    ";

    $stmt = $conn->prepare($sql);
    $searchParam = "%$search%";
    $stmt->bind_param('ssii', $searchParam, $searchParam, $limit, $offset);
    $stmt->execute();

    $result = $stmt->get_result();
    $shops = [];

    // ประมวลผลผลลัพธ์
    while ($row = $result->fetch_assoc()) {
        $shopId = $row['id'];

        // สร้างโครงสร้างข้อมูลร้านค้า
        if (!isset($shops[$shopId])) {
            $shops[$shopId] = [
                'id' => $row['id'],
                'owner_id' => $row['owner_id'],
                'shopname' => $row['shopname'],
                'image_url' => $row['image_url'],
                'username' => $row['username'],
                'qrcode' => $row['qrcode'],
                'menus' => []
            ];
        }

        // เพิ่มเมนูลงในร้านค้าที่เกี่ยวข้อง
        if ($row['menu_id']) {
            $shops[$shopId]['menus'][] = [
                'id' => $row['menu_id'],
                'menuname' => $row['menuname'],
                'image_url' => $row['menu_url'],
                'price' => $row['price'],
                'stock' => $row['stock'],
                'type' => $row['type']
            ];
        }
    }

    // แปลงข้อมูลร้านค้าเป็นรูปแบบ array เดียว
    return array_values($shops);
}


function deleteShop($conn, $id, $imageUrl)
{
    if (file_exists(SHOP_UPLOAD_DIR . $imageUrl)) {
        unlink(SHOP_UPLOAD_DIR . $imageUrl);
    }

    $sql = "DELETE FROM shops WHERE id = $id";
    $conn->query($sql);
}

function createShop($conn, $owner_id, $shopname, $image_url, $qrcode)
{
    $sql = "INSERT INTO shops (owner_id, shopname, image_url , qrcode) VALUES ('$owner_id', '$shopname', '$image_url', '$qrcode')";

    if ($conn->query($sql) === TRUE) {
        return getShopById($conn, $conn->insert_id);
    }

    return null;
}

function updateShop($conn, $id, $owner_id, $shopname, $image_url , $qrcode)
{
    $sql = "UPDATE shops SET owner_id = '$owner_id', shopname = '$shopname', image_url = '$image_url' , qrcode = '$qrcode'  WHERE id = $id";

    $conn->query($sql);

    return getShopById($conn, $id);
}
