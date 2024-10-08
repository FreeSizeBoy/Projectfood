<?php
require_once 'database.php';
require_once 'sevice/orders.php';
require_once 'sevice/upload.php';

$filter = $_GET['filter'] ?? '';

$orders = getOrders($conn, $filter);

echo json_encode([
    'status' => true,
    'data' => $orders
]);
