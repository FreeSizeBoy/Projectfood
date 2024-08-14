<?php
require_once 'database.php';
require_once 'sevice/food.php';
require_once 'sevice/upload.php';

$page = $_GET['page'] ?? 1;
$limit = $_GET['limit'] ?? 10;
$search = $_GET['search'] ?? '';

$foods = getMenus($conn, $page, $limit, $search);

echo json_encode([
    'status' => true,
    'data' => $foods
]);
