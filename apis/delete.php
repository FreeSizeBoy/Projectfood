<?php

require_once 'database.php';

$id = $parameters[0];

$sql = "DELETE  FROM users WHERE id = '$id'";
$result = $conn->query($sql);

echo json_encode([
    'status' => true,
    'massage' => 'ลบข้อมูลเรียบร้อย',
]);

?>