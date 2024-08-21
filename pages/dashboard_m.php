<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - คำสั่งซื้อ</title>
    <link rel="stylesheet" href="css/dashboard_m.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <h1>คำสั่งซื้อทั้งหมด</h1>
        </header>
        <main class="dashboard-main">
            <a href="dashboard" class="back-link">← กลับ</a>
            <table id="ordersTable" class="orders-table">
                <thead>
                    <tr>
                        <th>ชื่อคนสั่ง</th>
                        <th>ห้อง</th>
                        <th>รายการอาหาร</th>
                        <th>ราคา</th>
                        <th>วิธีการชำระเงิน</th>
                        <th>สถานะ</th>
                        <th>การดำเนินการ</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- ข้อมูลจะถูกเพิ่มโดย JavaScript -->
                </tbody>
            </table>
        </main>
    </div>

    <script>
        $(document).ready(function() {
            const LOCAL_STORAGE_KEY = 'ordersData';

            function fetchOrders() {
                $.ajax({
                    url: '<?= ROOT_URL ?>/api/orders',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.status) {
                            const orders = data.data;
                            const previousOrders = JSON.parse(localStorage.getItem(LOCAL_STORAGE_KEY)) || [];
                            const newOrders = getNewOrders(previousOrders, orders);

                            if (newOrders.length > 0) {
                                showNewOrderAlert(newOrders);
                            }
                            
                            // อัปเดตตารางและเก็บข้อมูลคำสั่งซื้อใหม่ใน localStorage
                            localStorage.setItem(LOCAL_STORAGE_KEY, JSON.stringify(orders));
                            updateTable(orders);
                        } else {
                            alert('ไม่สามารถดึงข้อมูลคำสั่งซื้อได้');
                        }
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            }

            function getNewOrders(previousOrders, currentOrders) {
                const previousIds = new Set(previousOrders.map(order => order.id));
                return currentOrders.filter(order => !previousIds.has(order.id));
            }

            function showNewOrderAlert(newOrders) {
                Swal.fire({
                    icon: 'info',
                    title: 'มีรายการใหม่!',
                    text: `มี ${newOrders.length} รายการใหม่`,
                    confirmButtonText: 'ดูรายการ',
                    onClose: () => fetchOrders() // รีเฟรชข้อมูลหลังจากปิดการแจ้งเตือน
                });
            }

            function showLargeImage(imageUrl) {
                Swal.fire({
                    imageUrl: imageUrl,
                    imageAlt: 'Slip Image',
                    imageWidth: 600,  // กำหนดขนาดของรูปภาพ
                    imageHeight: 400,
                    imageClass: 'img-fluid',
                    confirmButtonText: 'ปิด'
                });
            }

            function updateTable(orders) {
                const tableBody = $('#ordersTable tbody');
                tableBody.empty(); // ล้างข้อมูลเก่า

                orders.forEach(order => {
                    if (order.status !== 'cancel' && order.status !== 'complete') {
                        const menuItems = order.details.map(item => {
                            const extraText = item.extra ? ` (เพิ่มเติม: ${item.extra})` : '';
                            const noteText = item.note ? ` (หมายเหตุ: ${item.note})` : '';
                            const formattedItem = `${item.menu_name} (${item.amount} x ฿${item.price})${extraText}${noteText}`;
                            return formattedItem;
                        }).join('<br>');

                        const statusText = getStatusText(order.status);

                        const row = `
                            <tr>
                                <td>${order.username}</td>
                                <td>${order.user_id}</td>
                                <td>${menuItems}</td>
                                <td>฿${order.total_price}</td>
                                <td>
                                    <img src="<?= SLIP_UPLOAD_DIR ?>${order.slip}" alt="${order.slip}" 
                                         style="width: 50px; height: auto; cursor: pointer;" 
                                         class="slip-image" data-image="<?= SLIP_UPLOAD_DIR ?>${order.slip}">
                                </td>
                                <td>${statusText}</td>
                                <td>
                                    <button class="confirm-order-btn" data-id="${order.id}" data-status="confirm">รับออเดอร์</button>
                                    <button class="complete-btn" data-id="${order.id}" data-status="complete">อาหารเสร็จแล้ว</button>
                                    <button class="cancel-btn" data-id="${order.id}" data-status="cancel">ยกเลิก</button>
                                </td>
                            </tr>
                        `;
                        tableBody.append(row);
                    }
                });

                // เพิ่ม event listeners สำหรับปุ่ม
                $('.confirm-order-btn').on('click', function() {
                    const id = $(this).data('id');
                    const status = $(this).data('status');
                    updateOrderStatus(id, status);
                });

                $('.complete-btn').on('click', function() {
                    const id = $(this).data('id');
                    const status = $(this).data('status');
                    updateOrderStatus(id, status);
                });

                $('.cancel-btn').on('click', function() {
                    const id = $(this).data('id');
                    const status = $(this).data('status');
                    if (confirm('คุณแน่ใจหรือไม่? การยกเลิกจะไม่สามารถกู้คืนได้!')) {
                        updateOrderStatus(id, status);
                    }
                });

                // เพิ่ม event listener สำหรับรูปภาพสลิป
                $('.slip-image').on('click', function() {
                    const imageUrl = $(this).data('image');
                    showLargeImage(imageUrl);
                });
            }

            function updateOrderStatus(id, status) {
                $.ajax({
                    url: `<?= ROOT_URL ?>/api/orders/${id}/edit`,
                    type: 'POST',
                    data: { status: status },
                    success: function(result) {
                        result = JSON.parse(result);
                        if (result.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ',
                                text: result.message
                            }).then(() => {
                                fetchOrders(); // ดึงข้อมูลใหม่หลังจากอัปเดต
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: result.message
                            });
                        }
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            }

            function getStatusText(status) {
                switch (status) {
                    case 'pending':
                        return 'รอการยืนยัน';
                    case 'confirm':
                        return 'ได้รับการยืนยัน';
                    case 'complete':
                        return 'อาหารเสร็จแล้ว';
                    case 'cancel':
                        return 'ยกเลิก';
                    default:
                        return 'ไม่ทราบสถานะ';
                }
            }

            // เรียกใช้งานฟังก์ชัน fetchOrders เพื่อโหลดข้อมูลเมื่อเริ่มต้น
            fetchOrders();

            // เรียกใช้งานฟังก์ชัน fetchOrders ทุกๆ 5 วินาที
            setInterval(fetchOrders, 5000);
        });
    </script>
</body>
</html>
