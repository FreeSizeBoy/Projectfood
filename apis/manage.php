<?php

require_once 'database.php';

$page = $_GET['page'] ?? 1;
$limit = $_GET['limit'] ?? 10;
$search = $_GET['search'] ?? '';

function getUsers($conn, $page = 1, $limit = 10, $search = '')
{
    $offset = ($page - 1) * $limit;

    $sql = "SELECT * FROM users WHERE username LIKE '%$search%' OR email LIKE '%$search%' OR fname LIKE '%$search%' OR lname LIKE '%$search%' LIMIT $limit OFFSET $offset";
    $result = $conn->query($sql);

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    return $users;
}

$users = getUsers($conn, $page, $limit, $search);

echo json_encode([
    'status' => true,
    'data' => $users
]);

?>