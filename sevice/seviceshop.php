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

function getshops($conn, $page = 1, $limit = 10, $search = '')
{
    $offset = ($page - 1) * $limit;

    // $sql = "SELECT * FROM shops  LIMIT $limit OFFSET $offset";
    $sql = "SELECT shops.*,users.username FROM shops JOIN users ON shops.owner_id = users.id LIMIT $limit OFFSET $offset";
    //$sql = "SELECT * FROM shops WHERE name LIKE '%$search%' OR description LIKE '%$search%' OR cardDescription LIKE '%$search%' LIMIT $limit OFFSET $offset";
    $result = $conn->query($sql);

    $shops = [];
    while ($row = $result->fetch_assoc()) {
        $shops[] = $row;
    }

    return $shops;
}

function deleteShop($conn, $id, $imageUrl)
{
    if (file_exists(SHOP_UPLOAD_DIR . $imageUrl)) {
        unlink(SHOP_UPLOAD_DIR . $imageUrl);
    }

    $sql = "DELETE FROM shops WHERE id = $id";
    $conn->query($sql);
}

function createShop($conn, $owner_id, $shopname, $image_url)
{
    $sql = "INSERT INTO shops (owner_id, shopname, image_url) VALUES ('$owner_id', '$shopname', '$image_url')";

    if ($conn->query($sql) === TRUE) {
        return getShopById($conn, $conn->insert_id);
    }

    return null;
}

function updateShop($conn, $id, $owner_id, $shopname, $image_url)
{
    $sql = "UPDATE shops SET owner_id = '$owner_id', shopname = '$shopname', image_url = '$image_url' WHERE id = $id";

    $conn->query($sql);

    return getShopById($conn, $id);
}
