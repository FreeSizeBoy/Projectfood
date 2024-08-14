<?php
require_once 'database.php';

$id = $parameters[0];

function getUserById($conn, $id)
{
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        return null;
    }

    return $result->fetch_assoc();
}

// if ($_SESSION['id'] !== $id) {
//     if ($_SESSION['role'] !== 'super_admin') {
//         echo json_encode([
//             'status' => false,
//             'massage' => 'ไม่สามารถดูข้อมูลผู้ใช้ได้'
//         ]);
//         return;
//     }
// }

$user = getUserById($conn, $id);

if ($user === null) {
    echo json_encode([
        'status' => false,
        'massage' => 'ไม่พบข้อมูลผู้ใช้'
    ]);
    return;
}

echo json_encode([
    'status' => true,
    'data' => $user
]);