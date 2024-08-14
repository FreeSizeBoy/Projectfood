<?php
require_once 'database.php';
require_once 'sevice/orders.php';
require_once 'sevice/upload.php';

// if ($_SESSION['role'] !== 'super_admin') {
//     echo json_encode([
//         'status' => false,
//         'massage' => 'ไม่สามารถสร้างร้านได้'
//     ]);
//     return;
// }

$user_id = $_POST['user_id'];
$menu_id = $_POST['menu_id'];
$price = $_POST['price'];
$status = $_POST['status'];

// function getUserById($conn, $id)
// {
//     $sql = "SELECT * FROM users WHERE id = $id";
//     $result = $conn->query($sql);

//     if ($result->num_rows == 0) {
//         return null;
//     }

//     return $result->fetch_assoc();
// }

$user = getUserById($conn, $user_id);

if ($user === null) {
    echo json_encode([
        'status' => false,
        'massage' => 'ไม่พบข้อมูลผู้ใช้'
    ]);
    return;
}

$imageUrl = $_FILES['slip']["name"] ?? null;

if ($imageUrl) {
    $imageUrl = uploadFile($_FILES['slip'], FOOD_UPLOAD_DIR);
    if (!$imageUrl['status']) {
        echo json_encode($imageUrl);        return;
    }
    $imageUrl = $imageUrl['filename']; 
} 

$orders = createOrders($conn, $user_id, $menu_id, $price, $status, $imageUrl);

if ($orders === null) {
    echo json_encode([
        'status' => false,
        'massage' => 'เกิดข้อผิดพลาด'
    ]);
    return;
}

echo json_encode([
    'status' => true,
    'massage' => 'คำสั่งซื้อสำเร็จ',
]);
