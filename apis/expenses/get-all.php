<?php
require_once 'database.php';
require_once 'sevice/expenses.php';

$shop_id = $_GET['shop_id'] ?? null;

$Expenses = getExpenses($conn, $shop_id);

echo json_encode([
    'status' => true,
    'data' => $Expenses
]);
