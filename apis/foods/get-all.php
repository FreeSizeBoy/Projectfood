<?php
require_once 'database.php';
require_once 'sevice/food.php';
require_once 'sevice/upload.php';

$shop_id = $_GET['shop_id'] ?? null;

$foods = getMenus($conn, $shop_id);

echo json_encode([
    'status' => true,
    'data' => $foods
]);
