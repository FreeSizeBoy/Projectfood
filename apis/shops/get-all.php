<?php
require_once 'database.php';
require_once 'sevice/seviceshop.php';
require_once 'sevice/upload.php';

$page = $_GET['page'] ?? 1;
$limit = $_GET['limit'] ?? 1000;
$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? '';

$shop = getshops($conn, $page, $limit, $search , $filter);

echo json_encode([
    'status' => true,
    'data' => $shop
]);

