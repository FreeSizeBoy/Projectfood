<?php
require_once 'database.php';
require_once 'sevice/seviceshop.php';
require_once 'sevice/upload.php';

if ($_SESSION['role'] !== 'super_admin') {
    echo json_encode([
        'status' => false,
        'massage' => 'ไม่สามารถสร้างร้านได้'
    ]);
    return;
}

function getUserById($conn, $id)
{
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        return null;
    }

    return $result->fetch_assoc();
}

$owner_id = $_POST['owner_id'];
$shopname = $_POST['shopname'];

$user = getUserById($conn, $owner_id);

if ($user === null) {
    echo json_encode([
        'status' => false,
        'massage' => 'ไม่พบข้อมูลผู้ใช้'
    ]);
    return;
}

$imageUrl = uploadFile($_FILES['imageUrl'], SHOP_UPLOAD_DIR);
if (!$imageUrl['status']) {
    echo json_encode($imageUrl);
    return;
}

$imageUrl = $imageUrl['filename'];

$shop = createShop($conn, $owner_id, $shopname, $imageUrl);

if ($shop === null) {
    echo json_encode([
        'status' => false,
        'massage' => 'เกิดข้อผิดพลาด'
    ]);
    return;
}

echo json_encode([
    'status' => true,
    'massage' => 'สร้างร้านสำเร็จ',
]);
