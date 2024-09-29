<?php
require_once 'database.php';
require_once 'sevice/upload.php';

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

$user = getUserById($conn, $id);

if ($user === null) {
    echo json_encode([
        'status' => false,
        'massage' => 'ไม่พบข้อมูลผู้ใช้'
    ]);
    return;
}

// if ($user['role'] === 'super_admin' && $_SESSION['id'] != 1) {
//     echo json_encode([
//         'status' => false,
//         'massage' => 'ไม่สามารถแก้ไขผู้ใช้นี้ได้'
//     ]);
//     return;
// }

$email = $_POST['email'] ?? $user['email'];
$firstname = $_POST['fname'] ?? $user['fname'];
$lastname = $_POST['lname'] ?? $user['lname'];
$password = $_POST['password'] ?? null;
$role = $_POST['role'] ?? $user['role'] ;
$tel = $_POST['tel'] ?? $user['tel'] ;
$nickname = $_POST['nickname'] ?? $user['nickname'];
$student_id = $_POST['student_id'] ?? $user['student_id'];
$room = $_POST['room'] ?? $user['room'];
$username = $_POST['username'] ?? $firstname . " " . $lastname;
// $role = $role === 'admin' ? 'admin' : 'user';
$imageUrl = $_FILES['uploadImage']["name"] ?? null;

if ($imageUrl) {
    $imageUrl = uploadFile($_FILES['uploadImage'], USER_UPLOAD_DIR);
    if (!$imageUrl['status']) {
        echo json_encode($imageUrl);        return;
    }
    $imageUrl = $imageUrl['filename']; 
} else {
    $imageUrl = $user['img_url'] ?? null;
}

$_SESSION["img_url"] =  USER_UPLOAD_DIR . "/" .  $imageUrl;

if (empty($password)) {
    $password = $user['password'];
} else {
    $password = password_hash($password, PASSWORD_DEFAULT);
}

function getUserByemail($conn, $email)
{
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        return null;
    }

    return $result->fetch_assoc();
}

function updateUser($conn, $id, $username, $email, $firstname, $lastname, $role , $nickname, $tel , $password, $student_id, $room , $img_url)
{
    $user = getUserById($conn, $id);
    if ($user['email'] !== $email) {
        $user = getUserByemail($conn, $email);
        if ($user !== null) {
            return 'duplicate';
        }
    }

    $sql = "UPDATE users SET username = '$username', email = '$email', fname = '$firstname', lname = '$lastname', nickname = '$nickname' , password = '$password' , tel = '$tel' , role = '$role' , student_id = '$student_id' , room = '$room', img_url = '$img_url' WHERE id = $id";

    $conn->query($sql);

    return getUserById($conn, $id);
}

$user = updateUser($conn, $id, $username, $email, $firstname, $lastname, $role , $nickname, $tel , $password , $student_id, $room , $imageUrl);



if ($user === 'duplicate') {
    echo json_encode([
        'status' => false,
        'massage' => 'มีผู้ใช้อีเมลนี้แล้ว'
    ]);
    return;
}

if ($user === null) {
    echo json_encode([
        'status' => false,
        'massage' => 'เกิดข้อผิดพลาด'
    ]);
    return;
}

$_SESSION["username"] = $user['username'];

echo json_encode([
    'status' => true,
    'massage' => 'แก้ไขผู้ใช้งานเรียบร้อยแล้ว',
]);