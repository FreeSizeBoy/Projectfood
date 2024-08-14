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

function getMenus($conn, $page = 1, $limit = 10, $search = '')
{
    $offset = ($page - 1) * $limit;

    // $sql = "SELECT * FROM menus  LIMIT $limit OFFSET $offset";
    $sql = "SELECT * FROM menus WHERE menuname LIKE '%$search%' OR type LIKE '%$search%'  LIMIT $limit OFFSET $offset";
    $result = $conn->query($sql);

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
