<?php
require_once 'database.php';
require_once 'sevice/orders.php';
require_once 'sevice/upload.php';

$page = $_GET['page'] ?? 1;
$limit = $_GET['limit'] ?? 10;
$search = $_GET['search'] ?? '';

$orders = getOrders($conn, $page, $limit, $search);

echo json_encode([
    'status' => true,
    'data' => $orders
]);
