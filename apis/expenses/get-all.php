<?php
require_once 'database.php';
require_once 'sevice/expenses.php';

$page = $_GET['page'] ?? 1;
$limit = $_GET['limit'] ?? 1000;
$search = $_GET['search'] ?? '';
$shop_id = $_GET['shop_id'] ?? null;
$filter = $_GET['filter'] ?? '';


$Expenses = getExpenses($conn, $page, $limit, $search , $filter , $shop_id);

echo json_encode([
    'status' => true,
    'data' => $Expenses
]);
