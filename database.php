<?php
$HOST = "localhost";
$USER = "u642212680_rester";
$PASS = "Itseksan25756";
$DB = "u642212680_rester";

$conn = new mysqli($HOST, $USER, $PASS);

if ($conn->connect_error) {
    die("เชื่อมต่อไม่สำเร็จ " . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS $DB";
if (!$conn->query($sql)) {
    die("เกิดข้อผิดพลาดในการสร้างฐานข้อมูล " . $conn->error);
}

$conn->select_db($DB);

if ($conn->connect_error) {
    die("เชื่อมต่อไม่สำเร็จ " . $conn->connect_error);
}

require_once 'databases/createtable.php';
