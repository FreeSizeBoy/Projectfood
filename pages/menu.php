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
    include "component/head.php";
    ?>

    <div class="menu-main">
        <div class="menu-mains">
            <a href="menu" class="active">เมนู</a>
        </div>
    </div>

    <div id="menu-s"></div>

    <!-- Sample menu items. Uncomment and replace with dynamic content if necessary -->
    <!-- 
    <div class="menu-items">
        <h2>ร้านข้าวแกง ร้านที่ 1</h2>
    </div>
    <div class="menu-itemss">
        <div class="menu-item">
            <img src="img/food1.png" alt="">
            <h4>ผัดกะเพรา 20 บาท</h4>
            <button type="button" class="btn btn-primary" data-shopid="1" data-id="1" data-action="order">สั่งซื้อ</button>
        </div>
        ...
    </div> 
    -->

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        //โค้ดนี้ใช้สำหรับเพิ่มไอเทม (item) ลงในรถเข็น (cart)
        function addToCart(shopId, foodId) {
            var cart = JSON.parse(localStorage.getItem('cart')) || [];
            
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
            //บันทึกข้อมูล
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCount();
        }

        // Remove item from cart
        function removeFromCart(shopId, foodId) {
            var cart = JSON.parse(localStorage.getItem('cart')) || [];
            cart = cart.filter(item => item.shopId == shopId || (item.id != foodId));
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCount();
        }

        // Update cart count in the header
        function updateCartCount() {
            var cart = JSON.parse(localStorage.getItem('cart')) || [];
            var count = cart.reduce((acc, item) => acc + item.quantity, 0);
            $('#cart-count').text(count);
        }

        // Handle order button clicks
        $(document).on('click', 'button[data-action="order"]', function() {
            var shopId = $(this).data('shopid');
            var foodId = $(this).data('id');
            addToCart(shopId, foodId);
            console.log("Added food with ID: " + foodId + " from shop with ID: " + shopId + " to cart.");
        });

        // Handle ordercart button clicks
        $(document).on('click', 'button[data-action="ordercart"]', function() { 
            var shopId = $(this).data('shopid');
            var foodId = $(this).data('id');
            addToCart(shopId, foodId);
            console.log("Added food with ID: " + foodId + " from shop with ID: " + shopId + " to cart.");
        });

        // Load menu data
        $(document).ready(() => {
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
                            menu_s.append(`
                                <div class="menu-items">
                                    <h2>${menu.shopname}</h2>
                                </div>
                                <div class="menu-itemss"></div>
                            `);
                            const menu_itemss = $(`<div class="menu-itemss"></div>`);
                            menu_s.append(menu_itemss);
                            menu.menus.forEach(menu_item => {
                                menu_itemss.append(`
                                    <div class="menu-item">
                                        <img src="<?= FOOD_UPLOAD_DIR ?>${menu_item.image_url}" alt="">
                                        <h4>${menu_item.menuname} ${menu_item.price} บาท</h4>
                                        <button class="btn btn-primary" type="button" data-shopid='${menu.id}' data-action='order' data-id="${menu_item.id}">สั่งซื้อ</button>
                                        <button class="btn btn-secondary" type="button" data-shopid='${menu.id}' data-action='ordercart' data-id="${menu_item.id}">+</button>
                                    </div>
                                `);
                            });
                        });
                        updateCartCount();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: response.message
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

        // Function to check if user is logged in
        function isLoggedIn() {
            // You can modify this based on how you check login status in your application
            // For example, you might check for a session or a specific cookie
            return '<?= $_SESSION['id'] ?? null ?>' != "";
        }

        // Handle order button clicks
        $(document).on('click', 'button[data-action="order"],button[data-action="ordercart"]', function() {
            if (!isLoggedIn()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเข้าสู่ระบบ',
                    text: 'คุณต้องเข้าสู่ระบบหรือสมัครสมาชิกก่อนทำการสั่งซื้อ',
                    showCancelButton: true,
                    confirmButtonText: 'เข้าสู่ระบบ',
                    cancelButtonText: 'สมัครสมาชิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'login'; // Redirect to login page
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        window.location.href = 'register'; // Redirect to register page
                    }
                });
            }
        });
    </script>
</body>

</html>