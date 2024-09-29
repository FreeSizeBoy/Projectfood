<?php

require_once "database.php";

$sql = "CREATE TABLE IF NOT EXISTS users(
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY ,
    email VARCHAR(100) NOT NULL ,
    password VARCHAR(255) NOT NULL,
    role ENUM('super_admin','admin','manager','student') ,
    fname VARCHAR(100) NOT NULL,
    lname VARCHAR(100) NOT NULL,
    room VARCHAR(100),
    student_id VARCHAR(100),
    gender VARCHAR(100),
    tel VARCHAR(100),
    img_url VARCHAR(255),
    username VARCHAR(255) NOT NULL,
    nickname VARCHAR(255),
    birthday VARCHAR(255) NOT NULL
    )";

if (!$conn->query($sql)) {
    die("เชื่อมต่อไม่ได้");
}

$sql = "SELECT * FROM users WHERE email = 'super@men.com'";

$result = $conn->query($sql);

if ($result->num_rows == 0) {
    $password = password_hash("12345", PASSWORD_DEFAULT);
    $sql = "INSERT INTO users(email, password, role, fname, lname, username) VALUES('super@men.com','$password','super_admin','FreeSize','Sombut', 'Seksan')";

    if (!$conn->query($sql)) {
        die("เชื่อมต่อไม่ได้");
    }
}


$sql = "CREATE TABLE IF NOT EXISTS shops(
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY ,
        owner_id INT(6) NOT NULL ,
        shopname VARCHAR(255) NOT NULL,
        image_url VARCHAR(255) NOT NULL,
        qrcode VARCHAR(255) NOT NULL,
        category VARCHAR(255) NOT NULL,
        status VARCHAR(255) NOT NULL
        )";

if (!$conn->query($sql)) {
    die("เชื่อมต่อไม่ได้");
}

$sql = "CREATE TABLE IF NOT EXISTS menus(
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY ,
            shop_id INT(6) NOT NULL ,
            menuname VARCHAR(255) NOT NULL,
            image_url VARCHAR(255) NOT NULL,
            price INT(6) NOT NULL,
            stock INT(6) NOT NULL,
            type VARCHAR(255) NOT NULL
            )";

if (!$conn->query($sql)) {
    die("เชื่อมต่อไม่ได้");
}

$sql = "CREATE TABLE IF NOT EXISTS orders(
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY ,
                shop_id INT(6) NOT NULL,
                user_id INT(6) NOT NULL ,
                total_price INT(6) NOT NULL,
                status VARCHAR(255) NOT NULL,
                slip VARCHAR(255) ,
                createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )";

if (!$conn->query($sql)) {
    die("เชื่อมต่อไม่ได้");
}

$sql = "CREATE TABLE IF NOT EXISTS orders_details(
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY ,
                    orders_id INT(6) NOT NULL ,
                    menu_id INT(6) NOT NULL,
                    price INT(6) NOT NULL,
                    amount INT(6) NOT NULL,
                    note TEXT,
                    extra ENUM('พิเศษ','ไม่พิเศษ') NOT NULL
                    )";

if (!$conn->query($sql)) {
    die("เชื่อมต่อไม่ได้");
}

$sql = "CREATE TABLE IF NOT EXISTS Expenses(
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY ,
    shop_id INT(6) NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    priceout INT(6) NOT NULL,
    note VARCHAR(255) NOT NULL
    )";

if (!$conn->query($sql)) {
    die("เชื่อมต่อไม่ได้");
}

$sql = "CREATE TABLE IF NOT EXISTS site_images(
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY ,
    title VARCHAR(255) NOT NULL,
    image_url VARCHAR(255),
    default_url VARCHAR(255) NOT NULL
    )";

if (!$conn->query($sql)) {
    die("เชื่อมต่อไม่ได้");
}