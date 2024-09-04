<?php
require_once 'database.php';
require_once 'sevice/food.php';
require_once 'sevice/upload.php';

$page = $_GET['page'] ?? 1;
$limit = $_GET['limit'] ?? 1000;
$search = $_GET['search'] ?? '';
$shop_id = $_GET['shop_id'] ?? null;
$filter = $_GET['filter'] ?? '';


$foods = getMenus($conn, $page, $limit, $search ,$filter, $shop_id);

echo json_encode([
    'status' => true,
    'data' => $foods
]);
