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
<?php
    include_once "component/dashborad.php";
    $id = $_SESSION['id'];
    $role = $_SESSION['role'];
?>

    <div class="main-content">
        <header>
            <h1>ยินดีต้อนรับสู่ Dashboard ของร้านอาหาร</h1>
        </header>
        <section class="dashboard">
            <h2>จัดการร้านอาหาร 
                <?php if ($role !== 'admin'): ?>
                    <button class="Add" data-action="Add" onclick="openAddmodal()">เพิ่ม</button>
                <?php endif; ?>
            </h2>

            <table id="table" class="dashboard-table">
                <thead>
                    <tr>
                        <th data-sort="id">ID</th>
                        <th>เจ้าของร้าน</th>
                        <th>ชื่อร้าน</th>
                        <th>รูปภาพ</th>
                        <th>QR-Code</th>
                        <th>แก้ไข</th>
                        <th>ลบ</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- <tr>
                        <td>1</td>
                        <td>Men</td>
                        <td>ร้านข้าวแกง</td>
                        <td><img src="img/egg.png" alt="" class="restaurant-img"></td>
                        <td><button class="edit-button">🖉</button></td>
                        <td><button class="delete-button">🗑️</button></td>
                    </tr> -->
                    <!-- เพิ่มแถวเพิ่มเติมที่นี่ -->
                </tbody>
            </table>
        </section>

        <!-- Modal แก้ไขร้านอาหาร -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>แก้ไขร้านอาหาร</h2>
                <form id="editForm">
                    <input type="hidden" id="shopId" name="restaurantId">
                    <label for="owner_id">เจ้าของร้าน:</label>
                    <input type="text" id="owner_id" name="owner_id" required>
                    <label for="shopname">ชื่อร้าน:</label>
                    <input type="text" id="shopname" name="shopname" required>
                    <label for="imageUrl">รูปภาพ:</label>
                    <img id="pre_EditimageUrl" src="https://via.placeholder.com/150?text=Profile+Picture" alt="Profile Picture">
                    <input type="file" id="EditimageUrl" name="imageUrl" accept="image/*" onchange="previewImage('EditimageUrl')">
                    <label for="qrcode">QR-Code</label>
                    <img id="pre_Editqrcode" src="https://via.placeholder.com/150?text=Profile+Picture" alt="QR-Code">
                    <input type="file" id="Editqrcode" name="qrcode" accept="image/*" onchange="previewImage('Editqrcode')">
                    <button type="submit">บันทึก</button>
                </form>
            </div>
        </div>

        <div id="AddModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>เพิ่มร้านอาหาร</h2>
                <form id="AddForm" data-form="AddForm">
                    <input type="hidden" id="restaurantId" name="restaurantId">
                    <label for="owner_id">เจ้าของร้าน:</label>
                    <input type="text" id="owner_id" name="owner_id" required>
                    <label for="shopname">ชื่อร้าน:</label>
                    <input type="text" id="shopname" name="shopname" required>
                    <label for="imageUrl">รูปภาพ:</label>
                    <img id="pre_AddimageUrl" src="https://via.placeholder.com/150?text=Profile+Picture" alt="Profile Picture">
                    <input type="file" id="AddimageUrl" name="imageUrl" accept="image/*" onchange="previewImage('AddimageUrl')">
                    <label for="qrcode">QR-Code:</label>
                    <img id="pre_Addqrcode" src="https://via.placeholder.com/150?text=Profile+Picture" alt="Qr-code">
                    <input type="file" id="Addqrcode" name="qrcode" accept="image/*" onchange="previewImage('Addqrcode')">
                    <button type="submit">บันทึก</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(() => {
            // ฟังก์ชันเรียงลำดับ
            function sortTable(column, order) {
                const table = $('#table');
                const tbody = table.find('tbody');
                const rows = tbody.find('tr').get();

                rows.sort((a, b) => {
                    const cellA = $(a).children('td').eq(column).text();
                    const cellB = $(b).children('td').eq(column).text();

                    if ($.isNumeric(cellA) && $.isNumeric(cellB)) {
                        return order === 'asc' ? cellA - cellB : cellB - cellA;
                    }

                    return order === 'asc' ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
                });

                $.each(rows, (index, row) => {
                    tbody.append(row);
                });
            }

            // เรียงลำดับเมื่อคลิกที่ส่วนหัวของตาราง
            $('#table thead th').on('click', function() {
                const column = $(this).index();
                const order = $(this).data('order') === 'asc' ? 'desc' : 'asc';
                $(this).data('order', order);
                sortTable(column, order);
            });

            // ส่วนอื่นๆ ของสคริปต์
            // ...

            $.ajax({
                url: '<?= ROOT_URL ?>/api/shops<?= $role == 'admin' ? "?filter=$id" : "" ?>',
                type: 'GET',
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.status) {
                        const shops = response.data;
                        const table = $('#table');
                        const tbody = table.find('tbody');
                        shops.forEach(shop => {
                            tbody.append(`
                                <tr>
                                    <td>${shop.id}</td>
                                    <td>${shop.username}</td>
                                    <td>${shop.shopname}</td>
                                    <td><img src="<?= SHOP_UPLOAD_DIR ?>${shop.image_url}" alt="Profile" id="profile-img" class="img-thumbnail" style="width: 38px; height: 38px; cursor: pointer;"></td>
                                    <td><img src="<?= PAYMENT_UPLOAD_DIR ?>${shop.qrcode}" alt="Profile" id="profile-img" class="img-thumbnail" style="width: 38px; height: 38px; cursor: pointer;"></td>
                                    <td><button data-id="${shop.id}" data-action="Edit" class="edit-button">🖉</button></td>
                                    <?php if ($role !== 'admin'): ?>
                                    <td><button data-id="${shop.id}" data-action="Delete" class="delete-button">🗑️</button></td>
                                    <?php else: ?>
                                    <td><button data-id="${shop.id}" data-action="Delete" class="delete-button" disabled>🗑️</button></td>
                                    <?php endif; ?>
                                </tr>
                            `);
                        });
                        table.on('click', 'button', (e) => {
                            const button = $(e.target);
                            const id = button.data('id');
                            const action = button.data('action');
                            console.log(action);
                            if (action === 'Edit') {
                                $('#editModal').show();
                                $('#shopId').val(id);
                                // ทำการดึงข้อมูลผู้ใช้งานและเติมข้อมูลในฟอร์ม
                                $.ajax({
                                    url: `<?= ROOT_URL ?>/api/shops/${id}`,
                                    type: 'GET',
                                    success: function(response) {
                                        console.log(response);
                                        response = JSON.parse(response);
                                        if (response.status) {
                                            const shop = response.data;
                                            $('#owner_id').val(shop.owner_id);
                                            $('#shopname').val(shop.shopname);
                                            $('#pre_EditimageUrl').attr('src', shop.image_url ? `<?= SHOP_UPLOAD_DIR ?>${shop.image_url}` : 'https://via.placeholder.com/150?text=Profile+Picture');
                                            $('#pre_Editqrcode').attr('src', shop.qrcode ? `<?= PAYMENT_UPLOAD_DIR ?>${shop.qrcode}` : 'https://via.placeholder.com/150?text=Qr-Code');
                                        }
                                    }
                                });
                            } else if (action === 'Delete') {
                                Swal.fire({
                                    title: 'คุณแน่ใจหรือไม่?',
                                    text: "คุณต้องการลบข้อมูลนี้!",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'ลบ',
                                    cancelButtonText: 'ยกเลิก'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $.ajax({
                                            url: `<?= ROOT_URL ?>/api/shops/${id}`,
                                            type: 'DELETE',
                                            success: function(response) {
                                                response = JSON.parse(response);
                                                if (response.status) {
                                                    Swal.fire('ลบแล้ว!', 'ข้อมูลของคุณถูกลบแล้ว.', 'success');
                                                    button.closest('tr').remove();
                                                } else {
                                                    Swal.fire('เกิดข้อผิดพลาด!', 'ไม่สามารถลบข้อมูลได้.', 'error');
                                                }
                                            }
                                        });
                                    }
                                });
                            }
                        });
                    }
                }
            });
        });

        // ฟังก์ชันดูตัวอย่างรูปภาพ
        function previewImage(inputId) {
            const input = document.getElementById(inputId);
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.getElementById(`pre_${inputId}`);
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        // เปิด modal เพิ่ม
        function openAddmodal() {
            $('#AddModal').show();
        }

        // ปิด modal
        $(document).on('click', '.close', function() {
            $(this).closest('.modal').hide();
        });

        // ดำเนินการเมื่อส่งฟอร์ม
        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            $.ajax({
                url: `<?= ROOT_URL ?>/api/shops/${$('#shopId').val()}`,
                type: 'PUT',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.status) {
                        Swal.fire('สำเร็จ!', 'ข้อมูลของคุณถูกอัพเดทแล้ว.', 'success');
                        $('#editModal').hide();
                        location.reload();
                    } else {
                        Swal.fire('เกิดข้อผิดพลาด!', 'ไม่สามารถอัพเดทข้อมูลได้.', 'error');
                    }
                }
            });
        });

        $('#AddForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            $.ajax({
                url: `<?= ROOT_URL ?>/api/shops`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.status) {
                        Swal.fire('สำเร็จ!', 'ข้อมูลของคุณถูกเพิ่มแล้ว.', 'success');
                        $('#AddModal').hide();
                        location.reload();
                    } else {
                        Swal.fire('เกิดข้อผิดพลาด!', 'ไม่สามารถเพิ่มข้อมูลได้.', 'error');
                    }
                }
            });
        });
    </script>
</body>

</html>
