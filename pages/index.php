<?php
$id = $_SESSION['id'] ?? null;
$img_url = $_SESSION['img_url'] ?? null;

include_once "database.php";



?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าหลัก</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


</head>

<body>
    <!-- header -->
    <?php
    include "component\head.php";
    ?>

    <div class="container-fluid containers">
        <div class="main">
            <img src="<?= getImageByTitle('welcome', $conn) ?>" alt="">
            <!-- <img src="img/welcome.png" alt=""> -->
            <h2>ยินดีต้อนรับเข้าสู่ระบบสั่งอาหาร 🍴</h2>
            <button type="button"><a href="menu">เมนูอาหาร</a></button>
        </div>
        <div class="container-fluid containers">
            <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="<?= getImageByTitle('res1', $conn) ?>" class="d-block w-100" alt="">
                        <!-- <img src="img/r1.png" class="d-block w-100" alt=""> -->
                    </div>
                    <div class="carousel-item">
                        <img src="img/r2.png" class="d-block w-100" alt="">
                    </div>
                    <div class="carousel-item">
                        <img src="img/r3.png" class="d-block w-100" alt="">
                    </div>
                    <div class="carousel-item">
                        <img src="img/r4.png" class="d-block w-100" alt="">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
    <!-- containner -->
    <div class="centar">
        <div class="main-img-cen">
            <img src="img/rain.jpg" alt="">
            <img src="img/restaurant4.png" alt="">
        </div>

        <div class="main-cen">
            <h2>restaurant (●'◡'●)</h2>
            <p>โรงเรียนเชียงกลาง“ประชาพัฒนา”ตั้งอยู่เลขที่ ๘๗ หมู่ ๑๑ ถนนอดุลย์เดชจรัส ตำบลเชียงกลาง อำเภอเชียงกลางจังหวัดน่านเดิมชื่อโรงเรียนประชาพัฒนาเริ่มก่อตั้งเมื่อปีการศึกษา ๒๕๐๓ มีเนื้อที่ ๗๙ ไร่เปิดทำการสอนครั้งแรกในระดับประถมศึกษาปีที่ ๕-๗ สังกัดกรมสามัญศึกษากระทรวงศึกษาธิการ มีนายสุนทรมณีศรีเป็นครูใหญ่</p>
        </div>
    </div>
    <!-- con -->
    <div class="main-down">
        <div class="main-down-logo">
            <h2>Happenings Foods</h2>
        </div>
        <div class="main-downs">
            <img src="img/food1.png" alt="">
            <img src="img/food2.png" alt="">
            <img src="img/egg1.png" alt="">
            <img src="img/nool1.png" alt="">
            <img src="img/nool2.png" alt="">
            <img src="img/nool3.png" alt="">
        </div>
    </div>
    <!-- Footer -->
    <footer class="main-footer-1">
        <div class="main-footer">
            <h4>ติดต่อ</h4>
            <p><a href="http://www.ckp.ac.th/mainpage"><i class="fa-brands fa-facebook"></i></a>: โรงเรียนเชียงกลางประชาพัฒนา</p>
        </div>
    </footer>

    <footer class="main-footer-2">
        <div class="main-footers">
            <p>ที่อยู่ : โรงเรียนเชียงกลางประชาพัฒนา</p>
            <p>สถานที่ : 1080 ตำบล เชียงกลาง อำเภอ เชียงกลาง น่าน 55160</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>