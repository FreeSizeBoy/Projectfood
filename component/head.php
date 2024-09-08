<?php
$id = $_SESSION['id'] ?? null;
$img_url = $_SESSION['img_url'] ?? Profile_defulte;
$username = $_SESSION['username'] ?? null;
?>

<div class="header">
    <div class="d-flex">
        <div class="menu">
            <div class="img-logo">
                <img src="img/logo (3).png" alt="">
            </div>
            <div><a href="<?= ROOT_URL ?>">หน้าหลัก</a></div>
            <div><a href="menu">เมนู</a></div>
        </div>
    </div>
    <div class="menu">
        <h2>โรงเรียนเชียงกลางประชาพัฒนา</h2>
    </div>
    <div class="menu">
        <?php if ($id) : ?>
            <h5 class="username"><?= $username ?></h5>
            <div class="dropdown">
                <img src="<?= $img_url ?>" alt="Profile" id="profile-img" class="img-thumbnail" style="width: 55px; height: 55px; cursor: pointer;">
                <a class="cart" href="<?= ROOT_URL ?>/cart">
                    🛒
                    <span id="cart-count" class="badge bg-danger">0</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="profile-img">
                    <a class="dropdown-item" href="<?= ROOT_URL ?>/profile">แก้ไขโปรไฟล์</a>
                    <a class="dropdown-item" href="<?= ROOT_URL ?>/order_history">ประวัติการสั่งซื้อ</a>
                    <a class="dropdown-item" href="<?= ROOT_URL ?>/api/logout">ล็อกเอาท์</a>
                </div>
            </div>
        <?php else : ?>
            <div><a href="register">สมัคร</a></div>
            <div><a href="login">เข้าสู่ระบบ</a></div>
        <?php endif; ?>
    </div>
</div>



    <script>
   document.addEventListener('DOMContentLoaded', function () {
    var profileImg = document.getElementById('profile-img');
    if (profileImg) {
        var dropdownMenu = new bootstrap.Dropdown(profileImg);

        profileImg.addEventListener('click', function () {
            dropdownMenu.toggle();
        });

        // Close dropdown when clicking outside
        window.addEventListener('click', function (event) {
            if (!profileImg.contains(event.target)) {
                dropdownMenu.hide();
            }
        });
    }
});
</script>