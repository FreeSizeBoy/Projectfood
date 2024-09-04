<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการสั่งซื้อ - ร้านอาหาร</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>

<?php

    include_once "component/dashborad.php"

?>
    <!-- <div class="sidebar">
        <h2>Dashboard</h2>
        <a href="profile">แก้ไขโปรไฟล์ Admin</a>
        <ul>
            <li><a href="dashboard">หน้าหลัก</a></li>
            <li><a href="manage">จัดการสมาชิก</a></li>
            <li><a href="food">จัดการเมนู</a></li>
            <li><a href="order">คำสั่งซื้อ</a></li>
            <li><a href="report">รายงานยอดขาย</a></li>
            <li><a href="setting">จัดการร้านอาหาร</a></li>
            <li><a href="dashboard_m">เปลี่ยนไปยังหน้าโทรศัพท์</a></li>
            <li><a href="<?= ROOT_URL ?>/api/logout">ออกจากระบบ</a></li>
        </ul>
    </div> -->
    <div class="main-content">
        <header>
            <h1>ประวัติการสั่งซื้อ</h1>
        </header>
        <section class="history">
            <h2>รายการคำสั่งซื้อ</h2>
            <table id="orderHistoryTable" class="history-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ชื่อผู้ใช้</th>
                        <th>อีเมล</th>
                        <th>วันที่สั่งซื้อ</th>
                        <th>เวลา</th>
                        <th>สถานะ</th>
                        <th>ราคา</th>
                        <th>จำนวน</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows will be populated by JavaScript -->
                </tbody>
            </table>
        </section>
    </div>

    <script>
        $(document).ready(() => {
            // Load order history data
            $.ajax({
                url: '<?= ROOT_URL ?>/api/orders/history',
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    response = JSON.parse(response);
                    if (response.status) {
                        const orders = response.data;

                        // Sort orders by ID in descending order (latest first)
                        orders.sort((a, b) => b.id - a.id);

                        const table = $('#orderHistoryTable');
                        const tbody = table.find('tbody');
                        tbody.empty(); // Clear existing rows

                        orders.forEach(order => {
                            tbody.append(`
                                <tr>
                                    <td>${order.id}</td>
                                    <td>${order.username}</td>
                                    <td>${order.email}</td>
                                    <td>${order.order_date}</td>
                                    <td>${order.order_time}</td>
                                    <td>${order.status}</td>
                                    <td>${order.total_price}</td>
                                    <td>${order.quantity}</td>
                                </tr>
                            `);
                        });
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
