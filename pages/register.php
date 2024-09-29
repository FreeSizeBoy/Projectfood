<?php

if (isset($_SESSION['id'])) {
    if ($_SESSION['role'] === 'super_admin') {
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
                <div class="welcomes">
                    <h2>ยินดีต้อนรับสู่หน้า สมัคร</h2>
                </div>
                <form data-form="register">

                    <div class="wrap">

                        <label for="frist_name">ชื่อ</label>
                        <input id="frist_name" name="frist_name" type="text" class="input" required>
                    </div>

                    <div class="wrap">
                        <label for="last_name">นามสกุล</label>
                        <input id="last_name" name="last_name" type="text" class="input" required>
                    </div>

                    <div class="wrap">
                        <label for="email">อีเมล</label>
                        <input id="email" name="email" type="text" class="input" required>
                    </div>
                    <div class="wrap">
                        <label for="password">รหัสผ่าน</label>
                        <input id="password" name="password" type="password" class="input" data-type="password" required>
                        <span class="toggle-password" onclick="togglePassword('password')">👀</span>
                    </div>

                    <div class="wrap">
                        <label for="confirm_password">ยืนยันรหัสผ่าน</label>
                        <input id="confirm_password" name="confirm_password" type="password" class="input" data-type="password" required>
                        <span class="toggle-password" onclick="togglePassword('confirm_password')">👀</span>
                    </div>

                    <div class="wrap">
                        <label for="classroom">ห้องเรียน</label>
                        <input id="classroom" name="classroom" type="text" class="input" required>
                    </div>

                    <div class="wrap">
                        <label for="gender">เพศ</label>
                        <select id="gender" name="gender" class="input" required>
                            <option value="เลือก" disabled selected>เลือกเพศ</option>
                            <option value="ชาย">ชาย</option>
                            <option value="หญิง">หญิง</option>
                            <option value="อื่นๆ">อื่น ๆ</option>
                        </select>
                    </div>

                    <!-- <div class="wrap">
                        <label for="gender">เพศ</label>
                        <input id="gender" name="gender" class="input" required>
                    </div> -->



                    <button type="submit" class="button">
                        <h1 class="sign">สมัคร</h1>
                    </button>
                </form>
            </div>
            <img class="login-logo" src="<?= getImageByTitle('welcome', $conn) ?>" alt="Logo">
            <div class="details-two">
                <h1 class="back">ยินดีต้อนรับ</h1>
                <p class="log">สมัครเพื่อทำการสั่งอาหาร</p>
                <h2 class="acc">ถ้าคุณมีสมัครแล้ว?<br><span class="loginr"><a href="login">เข้าสู่ระบบ?</a></span></h2>
            </div>
        </div>
    </body>

    </html>

    <script>
        function togglePassword(id) {
            var input = document.getElementById(id);
            var type = input.getAttribute('type');
            if (type === 'password') {
                input.setAttribute('type', 'text');
            } else {
                input.setAttribute('type', 'password');
            }
        }
    </script>

    <script>

        
        $('[data-form="register"]').on('submit', (e) => {
            e.preventDefault();
            $('button[type="submit"]').prop('disabled', true);
            const data = $(e.target).serializeArray();
            $.ajax({
                url: '<?= ROOT_URL ?>/api/register',
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
                            location.href='<?= ROOT_URL ?>/login';
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