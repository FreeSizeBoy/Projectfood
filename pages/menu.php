<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เมนูอาหาร</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>

    <?php
    include "component\head.php";

    ?>

    <div class="menu-main">
        <div class="menu-mains">
            <a href="menu" class="active">เมนู</a>
        </div>
    </div>

    <div id="menu-s">

    </div>

    <!-- <div class="menu-items">
        <h2>ร้านข้าวแกง ร้านที่ 1</h2>
    </div>
    <div class="menu-itemss">
        <div class="menu-item">
            <img src="img/food1.png" alt="">
            <h4>ผัดกะเพรา 20 บาท</h4>
            <button type="button">สั่งซื้อ</button>
        </div>
        <div class="menu-item">
            <img src="img/food2.png" alt="">
            <h4>ไก่ทอด 20 บาท</h4>
            <button type="button">สั่งซื้อ</button>
        </div>
        <div class="menu-item">
            <img src="img/food3.png" alt="">
            <h4>ยำรวมมิตร 20 บาท</h4>
            <button type="button">สั่งซื้อ</button>
        </div>
        <div class="menu-item">
            <img src="img/food4.png" alt="">
            <h4>ผัดบวบ 20 บาท</h4>
            <button type="button">สั่งซื้อ</button>
        </div>
        <div class="menu-item">
            <img src="img/food5.png" alt="">
            <h4>ลาบหมู 20 บาท</h4>
            <button type="button">สั่งซื้อ</button>
        </div>
    </div>

    <div class="menu-items">
        <h2>ร้านข้าวแกง ร้านที่ 2</h2>
    </div>
    <div class="menu-itemss">
        <div class="menu-item">
            <img src="img/chin.png" alt="">
            <h4>ไก่ทอด 20 บาท</h4>
            <button type="button">สั่งซื้อ</button>
        </div>
        <div class="menu-item">
            <img src="img/egg.png" alt="">
            <h4>ไข่ดาว 20 บาท</h4>
            <button type="button">สั่งซื้อ</button>
        </div>
        <div class="menu-item">
            <img src="img/egg1.png" alt="">
            <h4>ไข่ลูกเขย 20 บาท</h4>
            <button type="button">สั่งซื้อ</button>
        </div>
    </div>

    <div class="menu-items">
        <h2>ร้านก๋วยเตี๋ยว ร้านที่ 1</h2>
    </div>
    <div class="menu-itemss">
        <div class="menu-item">
            <img src="img/nool1.png" alt="">
            <h4>ก๋วยเตี๋ยวน้ำตก 20 บาท</h4>
            <button type="button">สั่งซื้อ</button>
        </div>
        <div class="menu-item">
            <img src="img/nool2.png" alt="">
            <h4>ก๋วยเตี๋ยวน้ำใส 20 บาท</h4>
            <button type="button">สั่งซื้อ</button>
        </div>
        <div class="menu-item">
            <img src="img/nool3.png" alt="">
            <h4>ก๋วยเตี๋ยวต้มยำ 20 บาท</h4>
            <button type="button">สั่งซื้อ</button>
        </div>
        <div class="menu-item">
            <img src="img/nool4.png" alt="">
            <h4>สุกี้ 20 บาท</h4>
            <button type="button">สั่งซื้อ</button>
        </div>
    </div>
    </div>

    <div class="menu-items">
        <h2>ร้านก๋วยเตี๋ยว ร้านที่ 2</h2>
    </div>
    <div class="menu-itemss">
        <div class="menu-item">
            <img src="img/tomyam.png" alt="">
            <h4>ผัดกะเพรา 20 บาท</h4>
            <button type="button">สั่งซื้อ</button>
        </div>
        <div class="menu-item">
            <img src="img/tomyam.png" alt="">
            <h4>ไก่ทอด 20 บาท</h4>
            <button type="button">สั่งซื้อ</button>
        </div>
        <div class="menu-item">
            <img src="img/tomyam.png" alt="">
            <h4>ส้มตำ 20 บาท</h4>
            <button type="button">สั่งซื้อ</button>
        </div>
        <div class="menu-item">
            <img src="img/tomyam.png" alt="">
            <h4>ข้าวมันไก่ 20 บาท</h4>
            <button type="button">สั่งซื้อ</button>
        </div>
        <div class="menu-item">
            <img src="img/tomyam.png" alt="">
            <h4>แกงเขียวหวาน 20 บาท</h4>
            <button type="button">สั่งซื้อ</button>
        </div>
        <div class="menu-item">
            <img src="img/tomyam.png" alt="">
            <h4>ข้าวหมูกรอบ 20 บาท</h4>
            <button type="button">สั่งซื้อ</button>
        </div>
    </div>
    </div> -->

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

    <script>
        function addToCart(shopId, foodId) {
            var cart = JSON.parse(localStorage.getItem('cart')) || [];

            // ลบตะกร้าของร้านค้าเก่าถ้ามี
            cart = cart.filter(item => item.shopId == shopId); 

            var found = false;

            cart = cart.map(function(item) {
                if (item.id == foodId) {
                    item.quantity += 1;
                    found = true;
                }
                return item;
            });

            if (!found) {
                cart.push({
                    shopId: shopId,
                    id: foodId,
                    quantity: 1
                });
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            
        }

        // ฟังก์ชันเพื่อเอาอาหารออกจากตะกร้า
        function removeFromCart(shopId, foodId) {
            var cart = JSON.parse(localStorage.getItem('cart')) || [];

            // ลบตะกร้าของร้านค้าเก่าถ้ามี
            cart = cart.filter(item => item.shopId == shopId || (item.id != foodId));

            localStorage.setItem('cart', JSON.stringify(cart));
            
        }

        // จัดการเหตุการณ์คลิกปุ่ม
        $(document).on('click', 'button[data-action="order"]', function() {
            var shopId = $(this).data('shopid');
            var foodId = $(this).data('id');
            addToCart(shopId, foodId);
            console.log("Added food with ID: " + foodId + " from shop with ID: " + shopId + " to cart.");
        });

        $(document).on('click', 'button[data-action="ordercart"]', function() {
            var shopId = $(this).data('shopid');
            var foodId = $(this).data('id');
            addToCart(shopId, foodId);
            console.log("Removed food with ID: " + foodId + " from shop with ID: " + shopId + " in cart.");
        });

        $(document).ready(() => {
            // Load menu data
            $.ajax({
                url: '<?= ROOT_URL ?>/api/shops',
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    response = JSON.parse(response);
                    if (response.status) {
                        const menus = response.data;
                        const menu_s = $('#menu-s');
                        menus.forEach(menu => {
                            console.log(menu);
                            //                         <div class="menu-items">
                            //     <h2>ร้านข้าวแกง ร้านที่ 1</h2>
                            // </div>
                            // <div class="menu-itemss">
                            //     <div class="menu-item">
                            //         <img src="img/food1.png" alt="">
                            //         <h4>ผัดกะเพรา 20 บาท</h4>
                            //         <button type="button">สั่งซื้อ</button>
                            //     </div>
                            //     <div class="menu-item">
                            //         <img src="img/food2.png" alt="">
                            //         <h4>ไก่ทอด 20 บาท</h4>
                            //         <button type="button">สั่งซื้อ</button>
                            //     </div>
                            //     <div class="menu-item">
                            //         <img src="img/food3.png" alt="">
                            //         <h4>ยำรวมมิตร 20 บาท</h4>
                            //         <button type="button">สั่งซื้อ</button>
                            //     </div>
                            //     <div class="menu-item">
                            //         <img src="img/food4.png" alt="">
                            //         <h4>ผัดบวบ 20 บาท</h4>
                            //         <button type="button">สั่งซื้อ</button>
                            //     </div>
                            //     <div class="menu-item">
                            //         <img src="img/food5.png" alt="">
                            //         <h4>ลาบหมู 20 บาท</h4>
                            //         <button type="button">สั่งซื้อ</button>
                            //     </div>
                            // </div>
                            menu_s.append(`
                            <div class="menu-items">
                                <h2>${menu.shopname}</h2>
                            </div>
                            `);
                            const menu_itemss = $(`<div class="menu-itemss"></div>`);
                            menu_s.append(menu_itemss);
                            menu.menus.forEach(menu_item => {
                                menu_itemss.append(`
                                <div class="menu-item">
                                    <img src="<?= FOOD_UPLOAD_DIR ?>${menu_item.image_url}" alt="">
                                    <h4>${menu_item.menuname} ${menu_item.price} บาท</h4>
                                    <button class="menuclick" type="button" data-shopid='${menu.id}' data-action='order' data-id="${menu_item.id}">สั่งซื้อ</button>
                                    <button class="menuclick" type="button" data-shopid='${menu.id}' data-action='ordercart' data-id="${menu_item.id}">+</button>
                                </div>
                            `);
                            });
                        });

                        // Event listeners for edit and delete buttons
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: response.massage
                        });
                    }
                },
                error: function(error) {
                    console.log(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด'
                    });
                }
            });
        });
    </script>

</body>

</html>