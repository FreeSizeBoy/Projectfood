<?php
require_once 'database.php';
require_once 'sevice/food.php';
require_once 'sevice/upload.php';

$page = $_GET['page'] ?? 1;
$limit = $_GET['limit'] ?? 1000;
$shop_id = $_GET['shop_id'] ?? null;



$foods = getMenus($conn, $page, $limit, $shop_id);

echo json_encode([
    'status' => true,
    'data' => $foods
]);
