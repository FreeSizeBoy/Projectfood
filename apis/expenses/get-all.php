<?php
require_once 'database.php';
require_once 'sevice/expenses.php';

$page = $_GET['page'] ?? 1;
$limit = $_GET['limit'] ?? 1000;
$shop_id = $_GET['shop_id'] ?? null;



$Expenses = getExpenses($conn, $page, $limit, $shop_id);

echo json_encode([
    'status' => true,
    'data' => $Expenses
]);
