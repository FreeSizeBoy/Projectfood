<?php

if (isset($_SESSION['id'])) {
    if ($_SESSION['role'] === 'super_admin' || $_SESSION['role'] === 'admin') {
        header('Location: ' . ROOT_URL . '/dashboard');
    } else {
        header('Location: ' . ROOT_URL);
    }
} 

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="<?= ROOT_URL ?>/css/logins.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>

<body>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<?php
    include "component\head.php";

    ?>
    <div class="login-form">
        <div class="details">
            <div class="welcome">
                <h2>ยินดีต้อนรับสู่หน้า Login</h2>
            </div>
            <form data-form="login">
                <div class="wrap">
                    <label for="email">อีเมล</label>
                    <input id="email" name="email" type="text" class="input">
                </div>
                <div class="wrap">
                    <label for="password">รหัสผ่าน</label>
                    <input id="password" name="password" type="password" class="input" data-type="password">
                </div>
                <div class="wrap">
                    <label>
                        <input type="checkbox" id="remember" name="remember">
                        จดจำรหัสผ่าน
                    </label>
                </div>
                <div class="wrap">
                    <label><a href="#">ลืมรหัสผ่าน</a></label>
                </div>
                <button type="submit" class="button">
                    <h1 class="sign">เข้าสู่ระบบ</h1>
                </button>
            </form>
        </div>
        <img class="login-logo" src="img/loginfor.png" alt="Logo">
        <div class="details-two">
            <h1 class="back">ยินดีต้อนรับ</h1>
            <p class="log">เข้าสู่ระบบเพื่อทำการสั่งอาหาร</p>
            <h2 class="acc">ถ้าคุณยังไม่ได้สมัคร?<br><span class="registers"><a href="register">สมัคร?</a></span></h2>
        </div>
    </div>
</body>
</html>

    <script>
        $('[data-form="login"]').on('submit', (e) => {
            e.preventDefault();
            $('button[type="submit"]').prop('disabled', true);
            const data = $(e.target).serializeArray();
            $.ajax({
                url: '<?= ROOT_URL ?>/api/login',
                type: 'POST',
                data: data,
                success: function(response) {
                    console.log(response);
                    response = JSON.parse(response);
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: response.massage,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        $('button[type="submit"]').prop('disabled', false);
                        Swal.fire({
                            icon: 'error',
                            title: response.massage
                        });
                    }
                },
                error: function(response) {
                    $('button[type="submit"]').prop('disabled', false);
                    console.log(response);
                    swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด'
                    });
                }
            });
        });
    </script>
</body>

</html>