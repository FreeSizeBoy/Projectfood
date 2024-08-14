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

function getOrders($conn, $page = 1, $limit = 10, $search = '')
{
    $offset = ($page - 1) * $limit;

    $sql = "SELECT orders.*, users.username ,users.nickname , users.email FROM orders JOIN users ON orders.user_id = users.id LIMIT $limit OFFSET $offset";
    // $sql = "SELECT * FROM orders WHERE users_name LIKE '%$search%' LIMIT $limit OFFSET $offset";
    $result = $conn->query($sql);

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    return $orders;
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

function updateOrders($conn, $id, $user_id, $menu_id, $price, $status, $slip)
{
    $sql = "UPDATE orders SET user_id = '$user_id', menu_id = '$menu_id', price = '$price', status = '$status', slip = '$slip' WHERE id = $id";

    $conn->query($sql);

    return getOrdersById($conn, $id);
}
