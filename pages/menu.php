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
    <style>
        .menu-item {
            position: relative;
            display: inline-block;
        }

        .out-of-stock {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            background-color: rgba(255, 0, 0, 0.7);
            color: white;
            font-size: 20px;
            padding: 10px 20px;
            z-index: 10;
            font-weight: bold;
            text-align: center;
        }

        .closed {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            font-size: 20px;
            padding: 10px 20px;
            z-index: 10;
            font-weight: bold;
            text-align: center;
        }
    </style>
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
            <p>มีปัญหาการใช้งานแจ้งได้ที่ : 092-6866123</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        function fetchMenuData() {
            $.ajax({
                url: '<?= ROOT_URL ?>/api/shops',
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    response = JSON.parse(response);
                    if (response.status) {
                        const menus = response.data;
                        const menu_s = $('#menu-s');
                        menu_s.empty(); // ล้างข้อมูลเดิม
                        menus.forEach(menu => {
                            let closedLabel = '';
                            if (menu.status === 'ปิด') {
                                closedLabel = `<div class="closed">ร้านปิด</div>`;
                            }

                            menu_s.append(`
                                <div id="shop-${menu.id}" class="menu-items" data-status="${menu.status}">
                                    <h2>${menu.shopname}</h2>
                                </div>
                                <div class="menu-itemss"></div>
                            `);
                            const menu_itemss = $(`<div class="menu-itemss"></div>`);
                            menu_s.append(menu_itemss);
                            menu.menus.forEach(menu_item => {
                                let stockLabel = '';
                                if (menu_item.stock <= 0) {
                                    stockLabel = `<div class="out-of-stock">สินค้าหมด</div>`;
                                }

                                menu_itemss.append(`
                                    <div class="menu-item position-relative">
                                        <img src="<?= FOOD_UPLOAD_DIR ?>${menu_item.image_url}" alt="">
                                        ${stockLabel}
                                        ${closedLabel}
                                        <h4>${menu_item.menuname} ${menu_item.price} บาท</h4>
                                        <button class="btn btn-primary" type="button" data-shopid='${menu.id}' data-action='order' data-id="${menu_item.id}" ${menu_item.stock <= 0 || menu.status === 'ปิด' ? 'disabled' : ''}>สั่งซื้อ</button>
                                        <button class="btn btn-secondary" type="button" data-shopid='${menu.id}' data-action='ordercart' data-id="${menu_item.id}" ${menu_item.stock <= 0 || menu.status === 'ปิด' ? 'disabled' : ''}>+</button>
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
        }

        function updateCartCount() {
            var cart = JSON.parse(localStorage.getItem('cart')) || [];
            var count = cart.reduce((acc, item) => acc + item.quantity, 0);
            $('#cart-count').text(count);
        }

        function addToCart(shopId, foodId) {
    var cart = JSON.parse(localStorage.getItem('cart')) || [];
    var shopStatus = $(`#shop-${shopId}`).data('status'); // ใช้ data attribute เพื่อตรวจสอบสถานะของร้านค้า

    if (shopStatus === 'closed') {
        Swal.fire({
            icon: 'error',
            title: 'ร้านปิด',
            text: 'ไม่สามารถเพิ่มสินค้าเมื่อร้านปิด'
        });
        return;
    }

    // ตรวจสอบว่ามีร้านค้าที่ไม่ใช่ร้านเดียวกับร้านที่เพิ่มใหม่หรือไม่
    var currentShop = cart.length > 0 ? cart[0].shopId : null;
    if (currentShop && currentShop !== shopId) {
        // ลบสินค้าทั้งหมดจากร้านเดิม
        cart = cart.filter(item => item.shopId === shopId);
    }

    // เพิ่มสินค้าจากร้านใหม่
    var item = cart.find(item => item.shopId === shopId && item.id === foodId);

    if (item) {
        item.quantity += 1;
    } else {
        cart.push({
            shopId: shopId,
            id: foodId,
            quantity: 1
        });
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
}


        // function removeFromCart(shopId, foodId) {
        //     var cart = JSON.parse(localStorage.getItem('cart')) || [];
        //     cart = cart.filter(item => !(item.shopId === shopId && item.id === foodId));
        //     localStorage.setItem('cart', JSON.stringify(cart));
        //     updateCartCount();
        // }
        $(document).ready(() => {
            fetchMenuData(); // โหลดข้อมูลเมนูเมื่อเริ่มต้น

            // รีเฟรชข้อมูลเมนูทุก 5 วินาที
            setInterval(fetchMenuData, 5000); 
        });


        $(document).on('click', 'button[data-action="order"], button[data-action="ordercart"]', function() {
    if (isLoggedIn()) {
        var shopId = $(this).data('shopid');
        var foodId = $(this).data('id');
        addToCart(shopId, foodId);
        console.log("Added food with ID: " + foodId + " from shop with ID: " + shopId + " to cart.");
        
        // If the action is "order", redirect to cart page
        if ($(this).data('action') === 'order') {
            location.href = '<?= ROOT_URL ?>/cart';
        }
    } else {
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

function isLoggedIn() {
    // Check if session ID is not null or undefined
    // console.log('<?= $_SESSION['id'] ?? null ?>' === "")
    return '<?= $_SESSION['id'] ?? null ?>' !== "";
}


    </script>
</body>

</html>
