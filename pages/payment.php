<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าชำระเงิน</title>
    <link rel="stylesheet" href="css/payment.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>

<body>
    <div class="payment-container">
        <h1>ชำระเงิน</h1>
        <div class="payment-methods">
            <div class="payment-option">
                <input type="radio" id="cash" name="payment-method" value="cash" checked>
                <label for="cash">ชำระเงินด้วยเงินสด</label>
            </div>
            <div class="payment-option">
                <input type="radio" id="qr" name="payment-method" value="qr">
                <label for="qr">สแกน QR Code</label>
            </div>
        </div>
        <div class="payment-details">
            <div class="qr-code" id="qr-code">
                <img src="img/QR Code.png" alt="QR Code" id="qr-image">
                <p>สแกน QR Code เพื่อชำระเงิน</p>
            </div>
            <div class="upload-slip" id="upload-slip">
                <label for="slip">อัปโหลดสลิปการชำระเงิน:</label>
                <input type="file" id="slip" name="slip" accept="image/*">
                <p id="upload-status"></p>
            </div>
            <div class="total-price">
                <p>รวม: <span id="total-amount">฿0</span></p>
            </div>
        </div>
        <div class="buttons">
            <button id="confirm-payment">ยืนยันการชำระเงิน</button>
            <button id="cancel-payment">ปิด</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const API_URL = '<?= ROOT_URL ?>/api/menus/';
    const ORDER_API_URL = '<?= ROOT_URL ?>/api/orders/create';
    const SHOP_API_URL = '<?= ROOT_URL ?>/api/shops/'; // URL ของ API สำหรับดึงข้อมูลร้านค้า

    async function updateTotalPrice() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        let total = 0;

        for (const item of cart) {
            try {
                const response = await fetch(`${API_URL}${item.id}`);
                const data = await response.json();

                if (data.status) {
                    const menuItem = data.data;
                    const price = parseFloat(menuItem.price);
                    const extraPrice = item.extra || 0;
                    const itemTotal = (price + extraPrice) * item.quantity;
                    total += itemTotal;
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        document.getElementById('total-amount').textContent = `฿${total.toFixed(2)}`;
    }

    updateTotalPrice();

    // Get elements
    const qrCodeSection = document.getElementById('qr-code');
    const uploadSlipSection = document.getElementById('upload-slip');
    const uploadInput = document.getElementById('slip');
    const uploadStatus = document.getElementById('upload-status');

    // Initial state: hide QR code and upload slip section
    qrCodeSection.style.display = 'none';
    uploadSlipSection.style.display = 'none';

    // Function to fetch QR Code from the shop API
    async function fetchQRCode(shopId) {
        try {
            const response = await fetch(`${SHOP_API_URL}${shopId}`);
            const data = await response.json();
            if (data.status) {
                document.getElementById('qr-image').src = '<?= PAYMENT_UPLOAD_DIR ?>' + data.data.qrcode; // Set QR Code image source
            } else {
                console.error('Error fetching QR Code:', data.message);
            }
        } catch (error) {
            console.error('Error fetching QR Code:', error);
        }
    }

    // Add event listeners for payment method change
    document.querySelectorAll('input[name="payment-method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'qr') {
                qrCodeSection.style.display = 'block';
                uploadSlipSection.style.display = 'block';
                const cart = JSON.parse(localStorage.getItem('cart')) || [];
                fetchQRCode(cart[0]['shopId']); // Fetch QR Code when QR payment is selected
            } else {
                qrCodeSection.style.display = 'none';
                uploadSlipSection.style.display = 'none';
            }
        });
    });

    // Handle file upload
    uploadInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            uploadStatus.textContent = `อัปโหลด: ${file.name}`;
        } else {
            uploadStatus.textContent = 'ไม่พบไฟล์';
        }
    });

    // Confirm payment
    document.getElementById('confirm-payment').addEventListener('click', function() {
        const paymentMethod = document.querySelector('input[name="payment-method"]:checked').value;
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        console.log(cart)
        if(cart.lenght == 0){
            Swal.fire({
                    icon: 'warning',
                    title: 'คำเตือน',
                    text: 'ยังไม่ได้เลือกอาหาร',
                });
                return;
        }
        const formData = new FormData();
        formData.append('user_id', <?= $_SESSION['id'] ?>); 
        formData.append('shop_id', cart[0]['shopId']); 
        formData.append('total_price', document.getElementById('total-amount').textContent.replace('฿', ''));
        formData.append('menu_id', JSON.stringify(cart)); 

        if (paymentMethod === 'qr') {
            if (uploadInput.files[0]) {
                formData.append('slip', uploadInput.files[0]);
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'คำเตือน',
                    text: 'กรุณาอัปโหลดสลิปการชำระเงิน',
                });
                return;
            }
        } else {
            formData.append('slip', 'เงินสด');
        }

        fetch(ORDER_API_URL, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {

                localStorage.clear();
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ',
                    text: 'การชำระเงินเสร็จสมบูรณ์',
                }).then(() => {
                    window.location.href = 'menu'; 
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาด',
                    text: data.message || 'เกิดข้อผิดพลาดในการชำระเงิน',
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'ข้อผิดพลาด',
                text: 'เกิดข้อผิดพลาดในการเชื่อมต่อ',
            });
        });
    });

    // Cancel payment
    document.getElementById('cancel-payment').addEventListener('click', function() {
        window.location.href = 'cart';
    });
});

    </script>
</body>

</html>
