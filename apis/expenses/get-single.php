<?php
require_once 'database.php';
require_once 'sevice/expenses.php';

$id = $parameters[0];

$Expenses = getExpensesById($conn, $id);

if ($Expenses === null) {
    echo json_encode([
        'status' => false,
        'massage' => 'ไม่พบข้อมูล'
    ]);
    return;
}

echo json_encode([
    'status' => true,
    'data' => $Expenses
]);
