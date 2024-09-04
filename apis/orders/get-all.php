<?php
require_once 'database.php';
require_once 'sevice/orders.php';
require_once 'sevice/upload.php';

$page = $_GET['page'] ?? 1;
$limit = $_GET['limit'] ?? 1000;
$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? '';

$orders = getOrders($conn, $page, $limit, $filter, $search);

echo json_encode([
    'status' => true,
    'data' => $orders
]);
