<?php

$role = $_SESSION['role'];

?>




<div class="sidebar">
        <h2>Dashboard</h2>
        <a href="profile">แก้ไขโปรไฟล์ Admin</a>
        <ul>
        <?php if ($role === 'super_admin') : ?> 
            <li><a href="dashboard">หน้าหลัก</a></li>
            <li><a href="manage">จัดการสมาชิก</a></li>
            <li><a href="food">จัดการเมนู</a></li>
            <li><a href="order">คำสั่งซื้อ</a></li>
            <li><a href="report">รายงานยอดขาย</a></li>
            <li><a href="expenses">รายจ่าย</a></li>
            <li><a href="setting">จัดการร้านอาหาร</a></li>
            <li><a href="dashboard_m">เปลี่ยนไปยังหน้าโทรศัพท์</a></li>
        <?php else : ?> 
            <li><a href="food">จัดการเมนู</a></li>
            <li><a href="order">คำสั่งซื้อ</a></li>
            <li><a href="report">รายงานยอดขาย</a></li>
            <li><a href="expenses">รายจ่าย</a></li>
            <li><a href="setting">จัดการร้านอาหาร</a></li>
            <li><a href="dashboard_m">เปลี่ยนไปยังหน้าโทรศัพท์</a></li>
            <?php endif; ?>
            <li><a href="<?= ROOT_URL ?>/api/logout">ออกจากระบบ</a></li>
        </ul>
    </div>
