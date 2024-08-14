<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ร้านอาหาร</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
            <h2>คำสั่งซื้อ <button class="Add" data-action="Add" onclick="openAddModal()">เพิ่ม</button></h2>
            <table id="orderTable" class="dashboard-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ชื่อผู้สั่งซื้อ</th>
                        <th>ชื่อเล่น</th>
                        <th>อีเมล</th>
                        <th>สถานะ</th>
                        <th>ราคา</th>
                        <th>วิธีชำระ</th>
                        <th>แก้ไข</th>
                        <th>ลบ</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows will be populated by JavaScript -->
                </tbody>
            </table>
        </section>

        <!-- Modal แก้ไขคำสั่งซื้อ -->
        <div id="editOrderModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>แก้ไขคำสั่งซื้อ</h2>
                <form id="editOrderForm" enctype="multipart/form-data">
                    <input type="hidden" id="editOrderId" name="orderId">
                    <label for="editUserId">ผู้ใช้:</label>
                    <input type="text" id="editUserId" name="userId" required>
                    
                    <label for="editMenuId">เมนู:</label>
                    <input type="text" id="editMenuId" name="menuId" required>
                    
                    <label for="editPrice">ราคา:</label>
                    <input type="number" id="editPrice" name="price" required>
                    
                    <label for="editOrderStatus">สถานะ:</label>
                    <select id="editOrderStatus" name="orderStatus" required>
                        <option value="Completed">สั่งซื้อเรียบร้อย</option>
                        <option value="Cancel">ยกเลิก</option>
                    </select>

                    <label for="editSlip">สลิปการชำระเงิน:</label>
                    <img id="editSlipPreview" src="https://via.placeholder.com/150?text=Slip+Image" alt="Order Image">
                    <input type="file" id="editSlip" name="slip" onchange="previewImage('editSlip', 'editSlipPreview')">
                    
                    <button type="submit">บันทึก</button>
                </form>
            </div>
        </div>

        <!-- Modal เพิ่มคำสั่งซื้อ -->
        <div id="addOrderModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>เพิ่มคำสั่งซื้อ</h2>
                <form id="addOrderForm" enctype="multipart/form-data">
                    <label for="addUserId">ผู้ใช้:</label>
                    <input type="text" id="addUserId" name="user_id" required>
                    
                    <label for="addMenuId">เมนู:</label>
                    <input type="text" id="addMenuId" name="menu_id" required>
                    
                    <label for="addPrice">ราคา:</label>
                    <input type="number" id="addPrice" name="price" required>
                    
                    <label for="addOrderStatus">สถานะ:</label>
                    <select id="addOrderStatus" name="status" required>
                        <option value="Completed">กำลังเตรียมอาหาร</option>
                        <option value="Cancel">อาหารเสร็จแล้ว</option>
                    </select>
                    
                    <label for="addSlip">สลิปการชำระเงิน:</label>
                    <img id="addSlipPreview" src="https://via.placeholder.com/150?text=Slip+Image" alt="Slip Image">
                    <input type="file" id="addSlip" name="slip" onchange="previewImage('addSlip', 'addSlipPreview')">
                    
                    <button type="submit">บันทึก</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(inputId, imgId) {
            const file = document.getElementById(inputId).files[0];
            const img = document.getElementById(imgId);
            if (file && img) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }

        $(document).ready(() => {
            // Load order data
            $.ajax({
                url: '<?= ROOT_URL ?>/api/orders',
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    response = JSON.parse(response);
                    if (response.status) {
                        const orders = response.data;
                        const table = $('#orderTable');
                        const tbody = table.find('tbody');
                        orders.forEach(order => {
                            tbody.append(`
                                <tr>
                                    <td>${order.id}</td>
                                    <td>${order.username}</td>
                                    <td>${order.nickname}</td>
                                    <td>${order.email}</td>
                                    <td>${order.status}</td>
                                    <td>${order.price}</td>
                                    <td>${order.slip}</td>
                                    <td><button data-id="${order.id}" data-action="Edit" class="edit-button">🖉</button></td>
                                    <td><button data-id="${order.id}" data-action="Delete" class="delete-button">🗑️</button></td>
                                </tr>
                            `);
                        });

                        // Event listeners for edit and delete buttons
                        table.on('click', 'button', (e) => {
                            const button = $(e.target);
                            const id = button.data('id');
                            const action = button.data('action');
                            if (action === 'Edit') {
                                $('#editOrderModal').show();
                                $('#editOrderId').val(id);
                                $.ajax({
                                    url: `<?= ROOT_URL ?>/api/orders/${id}`,
                                    type: 'GET',
                                    success: function(response) {
                                        console.log(response);
                                        response = JSON.parse(response);
                                        if (response.status) {
                                            const order = response.data;
                                            $('#editOrderId').val(order.id);
                                            $('#editUserId').val(order.userId);
                                            $('#editMenuId').val(order.menuId);
                                            $('#editPrice').val(order.price);
                                            $('#editOrderStatus').val(order.orderStatus);
                                            $('#editSlipPreview').attr('src', order.slip ? `<?= SLIP_UPLOAD_DIR ?>/${order.slip}` : 'https://via.placeholder.com/150?text=Slip+Image');
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: response.massage
                                            });
                                        }
                                    }
                                });
                            } else if (action === 'Delete') {
                                Swal.fire({
                                    title: 'คุณแน่ใจหรือไม่?',
                                    text: 'การลบข้อมูลจะไม่สามารถกู้คืนได้!',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#ff4081',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'ลบ',
                                    cancelButtonText: 'ยกเลิก'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $.ajax({
                                            url: `<?= ROOT_URL ?>/api/orders/${id}/delete`,
                                            type: 'POST',
                                            success: function(response) {
                                                response = JSON.parse(response);
                                                if (response.status) {
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: response.massage,
                                                        showConfirmButton: false,
                                                        timer: 1500
                                                    }).then(() => {
                                                        location.reload();
                                                    });
                                                } else {
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: response.massage
                                                    });
                                                }
                                            }
                                        });
                                    }
                                });
                            }
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

            // Handle form submission for editing
            $('#editOrderForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: `<?= ROOT_URL ?>/api/orders/${$('#editOrderId').val()}/edit`,
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    data: new FormData(this),
                    success: function(response) {
                        response = JSON.parse(response);
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: response.massage,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: response.massage
                            });
                        }
                    }
                });
            });

            // Handle form submission for adding
            $('#addOrderForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: `<?= ROOT_URL ?>/api/orders/create/`,
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    data: new FormData(this),
                    success: function(response) {
                        console.log(response);
                        response = JSON.parse(response);
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: response.massage,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: response.massage
                            });
                        }
                    }
                });
            });

            // Close modals
            $('.modal .close').on('click', function() {
                $(this).closest('.modal').hide();
            });

            window.openAddModal = function() {
                $('#addOrderModal').show();
            }
        });
    </script>
</body>
</html>
