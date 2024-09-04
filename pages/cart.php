<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@300;400;600;700&family=Charmonman:wght@400;700&family=Kanit:wght@300;400;600;700&display=swap">
    <link rel="stylesheet" href="css/cart.css">
</head>

<body>
    <div class="cart-container">
        <div class="cart-header">
            <h1>ตะกร้า</h1>
            <button class="close-btn" id="close-btn">&times;</button>
        </div>
        <div class="cart-items" id="cart-items">
            <!-- รายการตะกร้าจะถูกเพิ่มที่นี่ -->
        </div>
        <div class="cart-summary">
            <h3>รวมทั้งหมด:</h3>
            <p class="total" id="cart-total">฿0</p>
        </div>

        <div class="cart-buttons">
            <a href="payment" class="checkout-btn">ชำระเงิน</a>
            <a href="menu" class="continue-shopping-btn">เลือกซื้อสินค้าเพิ่มเติม</a>
            <button class="clear-cart-btn" id="clear-cart-btn">ล้างตะกร้า</button>
        </div>

    </div>

    <script>
        const API_URL = '<?= ROOT_URL ?>/api/menus/';

        async function updateCart() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const cartItems = document.getElementById('cart-items');
            const cartTotal = document.getElementById('cart-total');

            cartItems.innerHTML = ''; // Clear old items
            let total = 0;

            cart.sort((a, b) => a.id - b.id);

            for (const item of cart) {
                try {
                    const response = await fetch(`${API_URL}${item.id}`);
                    const data = await response.json();

                    if (data.status) {
                        const menuItem = data.data;
                        const price = parseFloat(menuItem.price); // แปลงราคาเป็นตัวเลข
                        const extraPrice = item.extra || 0; // ราคาที่เพิ่มจาก checkbox
                        const itemTotal = (price + extraPrice) * item.quantity; // คำนวณราคาทั้งหมด
                        total += itemTotal;

                        const cartItem = document.createElement('div');
                        cartItem.className = 'cart-item';
                        cartItem.innerHTML = `
                    <img src="<?= FOOD_UPLOAD_DIR ?>${menuItem.image_url}" alt="${menuItem.menuname}">
                    <div class="cart-item-details">
                        <h2 class="cart-item-name">${menuItem.menuname}</h2>
                        <p class="cart-item-price">ราคา: ฿${price + extraPrice}</p>
                        <div class="cart-item-controls">
                            <button class="quantity-btn decrease" data-shopid="${item.shopId}" data-foodid="${item.id}">-</button>
                            <span class="cart-item-quantity">${item.quantity}</span>
                            <button class="quantity-btn increase" data-shopid="${item.shopId}" data-foodid="${item.id}">+</button>
                        </div>
                        <p class="cart-item-total">รวม: ฿${itemTotal}</p>

                        <div class="cart-item-extra">
                            <input type="checkbox" id="extra-${item.id}" data-foodid="${item.id}" ${item.extra ? 'checked' : ''}>
                            <label for="extra-${item.id}">พิเศษ</label>
                            <textarea class="extra-text" id="extra-text-${item.id}" data-foodid="${item.id}" placeholder="กรุณากรอกข้อความเพิ่มเติม"></textarea>
                        </div>

                `;
                        cartItems.appendChild(cartItem);
                    }
                } catch (error) {
                    console.error('Error fetching data:', error);
                }
            }

            cartTotal.textContent = `฿${total}`;
        }

        function addToCart(shopId, foodId) {
            var cart = JSON.parse(localStorage.getItem('cart')) || [];

            // Remove old cart items from the same shop
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
            updateCart();
        }

        function minusToCart(shopId, foodId) {
            var cart = JSON.parse(localStorage.getItem('cart')) || [];

            // Remove old cart items from the same shop
            cart = cart.filter(item => item.shopId == shopId);
            cart = cart.map(function(item) {
                if (item.id == foodId) {
                    item.quantity -= 1;
                }
                return item;
            });

            cart = cart.filter(item => item.quantity > 0);

            localStorage.setItem('cart', JSON.stringify(cart));
            updateCart();
        }

        function removeFromCart(shopId, foodId) {
            var cart = JSON.parse(localStorage.getItem('cart')) || [];

            // Remove old cart items from the same shop
            cart = cart.filter(item => item.shopId == shopId || (item.id != foodId));

            localStorage.setItem('cart', JSON.stringify(cart));
            updateCart();
        }

        function clearCart() {
            localStorage.removeItem('cart');
            updateCart();
        }

        // Set up event handlers for increase and decrease buttons
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('increase')) {
                var shopId = event.target.getAttribute('data-shopid');
                var foodId = event.target.getAttribute('data-foodid');
                addToCart(shopId, foodId);
            } else if (event.target.classList.contains('decrease')) {
                var shopId = event.target.getAttribute('data-shopid');
                var foodId = event.target.getAttribute('data-foodid');
                minusToCart(shopId, foodId);
            } else if (event.target.id === 'clear-cart-btn') {
                clearCart();
            }
        });

        // Handle close button click
        document.getElementById('close-btn').addEventListener('click', function() {
            window.location.href = 'menu';
        });

        // Update cart on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCart();
        });

        // Handle textarea and checkbox changes
        document.addEventListener('input', function(event) {
            if (event.target.classList.contains('extra-text')) {
                var foodId = event.target.getAttribute('data-foodid');
                var extraText = event.target.value;
                var cart = JSON.parse(localStorage.getItem('cart')) || [];

                cart = cart.map(item => {
                    if (item.id == foodId) {
                        item.extraText = extraText;
                    }
                    return item;
                });

                localStorage.setItem('cart', JSON.stringify(cart));
            }
        });

        document.addEventListener('change', function(event) {
            if (event.target.type === 'checkbox') {
                var foodId = event.target.getAttribute('data-foodid');
                var isChecked = event.target.checked;
                var cart = JSON.parse(localStorage.getItem('cart')) || [];

                cart = cart.map(item => {
                    if (item.id == foodId) {
                        item.extra = isChecked ? 10 : 0; // เพิ่มราคาหรือคืนราคาเป็น 0
                    }
                    return item;
                });

                localStorage.setItem('cart', JSON.stringify(cart));
                updateCart();
            }
        });
    </script>
</body>

</html>