<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ร้านอาหาร</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
<div class="sidebar">
        <h2>Dashboard</h2>
        <a href="profile">แก้ไขโปรไฟล์ Admin</a>
        <ul>
            <li><a href="dashboard">หน้าหลัก</a></li>
            <li><a href="manage">จัดการสมาชิก</a></li>
            <li><a href="food">จัดการเมนู</a></li>
            <li><a href="order">คำสั่งซื้อ</a></li>
            <li><a href="report">รายงานยอดขาย</a></li>
            <li><a href="setting">จัดการร้านอาหาร</a></li>
            <li><a href="<?= ROOT_URL ?>/api/logout">ออกจากระบบ</a></li>
        </ul>
    </div>
    <div class="main-content">
    <header>
        <h1>ยินดีต้อนรับสู่ Dashboard ของร้านอาหาร</h1>
    </header>
    <section class="dashboard">
    <h2>ประวัติการสั่งซื้อทั้งหมด</h2>
    <table class="dashboard-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>ชื่อผู้ซื้อ</th>
                <th>อีเมล</th>
                <th>วันที่สั่งซื้อ</th>
                <th>เวลา</th>
                <th>สถานะ</th>
                <th>ราคา</th>
                <th>จำนวน</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>001</td>
                <td>John Doe</td>
                <td>john.doe@email.com</td>
                <td>2024-08-01</td>
                <td>14:30</td>
                <td>Completed</td>
                <td>฿300</td>
                <td>2</td>
            </tr>
            <!-- เพิ่มแถวข้อมูลเพิ่มเติมที่นี่ -->
        </tbody>
    </table>
</section>





</body>
</html>
