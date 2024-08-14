<?php
$HOST = "localhost";
$USER = "root";
$PASS = "";
$DB = "rester";

$conn = new mysqli($HOST, $USER, $PASS);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS $DB";
if (!$conn->query($sql)) {
    die("Error creating database: " . $conn->error);
}

$conn->select_db($DB);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

require_once 'databases/createtable.php';
