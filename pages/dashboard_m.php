<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - คำสั่งซื้อ</title>
    <link rel="stylesheet" href="css/dashboard_mob.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php
    $shop_id = $_SESSION['shop_id'] ?? null;
    $role = $_SESSION['role'];
    ?>
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
                    url: '<?= ROOT_URL ?>/api/orders<?= $role == 'admin' ? "?filter=$shop_id" : "" ?>',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.status) {
                            let orders = data.data;

                            // กรองเฉพาะสถานะที่ต้องการ เช่น pending, confirm
                            orders = orders.filter(order => order.status === 'pending' || order.status === 'confirm');

                            const previousOrders = JSON.parse(localStorage.getItem(LOCAL_STORAGE_KEY)) || [];

                            // หาก LocalStorage ยังไม่มีข้อมูล ให้แสดงแจ้งเตือนสำหรับรายการใหม่
                            if (previousOrders.length === 0) {
                                const newOrders = orders.filter(order => order.status === 'pending' || order.status === 'confirm');

                                if (newOrders.length > 0) {
                                    showNewOrderAlert(newOrders);
                                }
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
            // ฟังก์ชัน getNewOrders นี่คือการ เปรียบเทียบคำสั่งซื้อเก่า (previousOrders) กับคำสั่งซื้อใหม่ (currentOrders) โดยจะหาคำสั่งซื้อที่ ไม่มีในรายการเก่า เพื่อดูว่าคำสั่งซื้อใดที่เป็นคำสั่งซื้อใหม่.

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
                    imageWidth: 600, // กำหนดขนาดของรูปภาพ
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

                        // กำหนดคลาสตามสถานะ
                        let statusClass = '';
                        switch (order.status) {
                            case 'pending':
                                statusClass = 'status-pending';
                                break;
                            case 'confirm':
                                statusClass = 'status-confirm';
                                break;
                            case 'complete':
                                statusClass = 'status-complete';
                                break;
                            case 'cancel':
                                statusClass = 'status-cancel';
                                break;
                        }

                        const row = `
                <tr class="${statusClass}">
                    <td>${order.username}</td>
                    <td>${order.room}</td>
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
                    data: {
                        status: status
                    },
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