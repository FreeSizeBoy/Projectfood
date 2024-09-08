<?php
require_once("database.php");

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = '$email'";

    $result = $conn -> query($sql);

    if($result -> num_rows == 0){
        echo json_encode([
            'status' => false,
            'massage' => "คุณยังไม่ได้สมัคร"
        ]);
            return;
        
    }
    $user = $result -> fetch_assoc();
    if(!password_verify($password,$user['password'])){
        echo json_encode([
            'status' => false,
            'massage' => "รหัสผิดพลาด"
        ]);
            return;
    }

    $_SESSION["id"] = $user['id'];
    $_SESSION["role"] = $user['role'];
    $_SESSION["username"] = $user['username'];
    if($user['img_url']){
        $_SESSION["img_url"] =  USER_UPLOAD_DIR . "/" .  $user['img_url'];
    }

    $userid = $_SESSION['id'];

    $sql = "SELECT * FROM shops WHERE owner_id = '$userid' ";

    $result = $conn -> query($sql);

    if($result -> num_rows != 0){
        $user = $result -> fetch_assoc();

        $_SESSION['shop_id'] = $user["id"];
    }

    echo json_encode([
        'status' => true,
        'massage' => "เข้าสู่ระบบสำเร็จ"
    ]);
        return;
    
?>