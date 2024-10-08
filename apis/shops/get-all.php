<?php
require_once 'database.php';
require_once 'sevice/seviceshop.php';
require_once 'sevice/upload.php';

$filter = $_GET['filter'] ?? '';

$shop = getshops($conn,  $filter);

echo json_encode([
    'status' => true,
    'data' => $shop
]);

