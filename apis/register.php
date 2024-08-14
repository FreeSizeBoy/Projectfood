<?php

require_once 'database.php';

$email = trim($_POST['email']);
$firstname = trim($_POST['frist_name']);
$lastname = trim($_POST['last_name']);
$password = trim($_POST['password']);
$confirm_password = trim($_POST['confirm_password']);
$classroom = trim($_POST['classroom']);
$gender = trim($_POST['gender']);
$username = $firstname . " " . $lastname;


if ($password !== $confirm_password) {
    echo json_encode([
        'status' => false,
        'massage' => 'รหัสผ่านไม่ตรงกัน'
    ]);
    return;
}

$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo json_encode([
        'status' => false,
        'massage' => 'ชื่อผู้ใช้นี้มีอยู่แล้ว'
    ]);
    return;
}

$password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (email, fname, lname, room, gender, password ,role ,username) 
VALUES ('$email', '$firstname', '$lastname', '$classroom', '$gender', '$password' , 'student' , '$username')";
if ($conn->query($sql) === TRUE) {
    echo json_encode([
        'status' => true,
        'massage' => 'สมัครสมาชิกสำเร็จ'
    ]);
} else {
    echo json_encode([
        'status' => false,
        'massage' => 'เกิดข้อผิดพลาด'
    ]);
}
