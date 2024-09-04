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
    $shop_id = $_SESSION['shop_id'] ?? null;
    $role = $_SESSION['role'];
    ?>


    <div class="main-content">
        <header>
            <h1>ยินดีต้อนรับสู่ Dashboard ของร้านอาหาร</h1>
        </header>
        <section class="dashboard">
            <h2>จัดการเมนู <button class="Add" data-action="Add" onclick="openAddModal()">เพิ่ม</button></h2>
            <table class="dashboard-table" id="menuTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ร้านค้าที่</th>
                        <th>ชื่อเมนู</th>
                        <th>รูป</th>
                        <th>ราคา</th>
                        <th>สต็อก</th>
                        <th>ประเภค</th>
                        <th>แก้ไข</th>
                        <th>ลบ</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be added dynamically here -->
                </tbody>
            </table>

            <!-- Pagination Controls -->
            <div class="pagination">
                <button class="prev-page" disabled>ก่อนหน้า</button>
                <span class="page-info">หน้าที่ 1 จาก 1</span>
                <button class="next-page">ถัดไป</button>
            </div>
        </section>
        <!-- Modal แก้ไขเมนู -->
        <div id="editMenuModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>แก้ไขเมนู</h2>
                <form id="editMenuForm">
                    <input type="hidden" id="editMenuId" name="menuId">
                    <label for="shop_id">ร้านค้าที่:</label>
                    <input type="text" id="editShopresId" name="shop_id" <?= $role == 'admin' ? 'readonly' : '' ?>>
                    <label for="menuname">ชื่อเมนู:</label>
                    <input type="text" id="editMenusName" name="menuname" required>
                    <label for="type">ประเภท:</label>
                    <input type="text" id="type" name="type" required>
                    <label for="price">ราคา:</label>
                    <input type="number" id="editPrice" name="price" required>
                    <label for="stock">สต็อก:</label>
                    <input type="number" id="editStock" name="stock" required>
                    <label for="menusImage">รูปภาพ:</label>
                    <img id="pre_EditMenuImage" src="https://via.placeholder.com/150?text=Menu+Image" alt="Menu Image">
                    <input type="file" id="editMenuImage" name="imageUrl" accept="image/*" onchange="previewImage('editMenuImage', 'pre_EditMenuImage')">
                    <button type="submit">บันทึก</button>
                </form>
            </div>
        </div>

        <!-- Modal เพิ่มเมนู -->
        <div id="AddMenuModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>เพิ่มเมนู</h2>
                <form id="AddMenuForm">
                    <label for="shop_id">ร้านค้าที่:</label>
                    <input type="text" id="addShopresId" name="shop_id" value="<?= $shop_id ?>" required <?= $role == 'admin' ? 'readonly' : '' ?>>
                    <label for="menuname">ชื่อเมนู:</label>
                    <input type="text" id="addMenusName" name="menuname" required>
                    <label for="type">ประเภท:</label>
                    <input type="text" id="type" name="type" required>
                    <label for="price">ราคา:</label>
                    <input type="number" id="addPrice" name="price" required>
                    <label for="stock">สต็อก:</label>
                    <input type="number" id="addStock" name="stock" required>
                    <label for="menusImage">รูปภาพ:</label>
                    <img id="pre_AddMenuImage" src="https://via.placeholder.com/150?text=Menu+Image" alt="Menu Image">
                    <input type="file" id="addMenuImage" name="imageUrl" accept="image/*" onchange="previewImage('addMenuImage', 'pre_AddMenuImage')">
                    <button type="submit">บันทึก</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // ฟังก์ชันสำหรับแสดงภาพตัวอย่าง
        function previewImage(inputId, imgId) {
            const file = document.getElementById(inputId).files[0];
            const img = document.getElementById(imgId);
            if (file && img) {
                //สร้าง FileReader และอ่านไฟล์:
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }

        $(document).ready(() => {
            // Load menu data
            $.ajax({
                url: '<?= ROOT_URL ?>/api/menus<?= $role == 'admin' ? "?shop_id=$shop_id" : "" ?>',
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    response = JSON.parse(response);
                    if (response.status) {
                        const menus = response.data;
                        const table = $('#menuTable');
                        const tbody = table.find('tbody');
                        menus.forEach(menu => {
                            tbody.append(`
                                <tr>
                                    <td>${menu.id}</td>
                                    <td>${menu.shop_id}</td>
                                    <td>${menu.menuname}</td>
                                    <td><img src="<?= FOOD_UPLOAD_DIR ?>${menu.image_url}" alt="Profile" id="profile-img" class="img-thumbnail" style="width: 180px; height: 100px;  cursor: pointer;"></td>
                                    <td>${menu.price}</td>
                                    <td>${menu.stock}</td>
                                    <td>${menu.type}</td>
                                    <td><button data-id="${menu.id}" data-action="Edit" class="edit-button">🖉</button></td>
                                    <td><button data-id="${menu.id}" data-action="Delete" class="delete-button">🗑️</button></td>
                                </tr>
                            `);
                        });

                        // Event listeners for edit and delete buttons
                        table.on('click', 'button', (e) => {
                            const button = $(e.target);
                            const id = button.data('id');
                            const action = button.data('action');
                            if (action === 'Edit') {
                                $('#editMenuModal').show();
                                $('#editMenuId').val(id);
                                $.ajax({
                                    url: `<?= ROOT_URL ?>/api/menus/${id}`,
                                    type: 'GET',
                                    success: function(response) {
                                        console.log(response);
                                        response = JSON.parse(response);
                                        if (response.status) {
                                            const menu = response.data;
                                            $('#editMenuId').val(menu.id);
                                            $('#editShopresId').val(menu.shop_id);
                                            $('#editMenusName').val(menu.menuname);
                                            $('#editPrice').val(menu.price);
                                            $('#editStock').val(menu.stock);
                                            $('#type').val(menu.type);
                                            $('#pre_EditMenuImage').attr('src', menu.image_url ? `<?= FOOD_UPLOAD_DIR ?>/${menu.image_url}` : 'https://via.placeholder.com/150?text=Menu+Image');
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
                                            url: `<?= ROOT_URL ?>/api/menus/${id}/delete`,
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
            $('#editMenuForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: `<?= ROOT_URL ?>/api/menus/${$('#editMenuId').val()}/edit`,
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
            // Handle form submission for adding
            $('#AddMenuForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: `<?= ROOT_URL ?>/api/menus/create/`,
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
                $('#AddMenuModal').show();
            }
        });

        $(document).ready(() => {
            const itemsPerPage = 10;
            let currentPage = 1;
            let totalPages = 1;

            function loadMenus(page) {
                $.ajax({
                    url: '<?= ROOT_URL ?>/api/menus<?= $role == 'admin' ? "?shop_id=$shop_id" : "" ?>',
                    type: 'GET',
                    success: function(response) {
                        response = JSON.parse(response);
                        if (response.status) {
                            const menus = response.data;
                            totalPages = Math.ceil(menus.length / itemsPerPage);
                            displayMenus(menus.slice((page - 1) * itemsPerPage, page * itemsPerPage));
                            updatePagination();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: response.massage
                            });
                        }
                    },
                    error: function(error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด'
                        });
                    }
                });
            }

            function displayMenus(menus) {
                const tbody = $('#menuTable').find('tbody');
                tbody.empty();
                menus.forEach(menu => {
                    tbody.append(`
                <tr>
                    <td>${menu.id}</td>
                    <td>${menu.shop_id}</td>
                    <td>${menu.menuname}</td>
                    <td><img src="<?= FOOD_UPLOAD_DIR ?>${menu.image_url}" alt="Profile" id="profile-img" class="img-thumbnail" style="width: 180px; height: 100px;  cursor: pointer;"></td>
                    <td>${menu.price}</td>
                    <td>${menu.stock}</td>
                    <td>${menu.type}</td>
                    <td><button data-id="${menu.id}" data-action="Edit" class="edit-button">🖉</button></td>
                    <td><button data-id="${menu.id}" data-action="Delete" class="delete-button">🗑️</button></td>
                </tr>
            `);
                });
            }

            function updatePagination() {
                $('.page-info').text(`หน้าที่ ${currentPage} จาก ${totalPages}`);
                $('.prev-page').prop('disabled', currentPage === 1);
                $('.next-page').prop('disabled', currentPage === totalPages);
            }

            $('.prev-page').click(() => {
                if (currentPage > 1) {
                    currentPage--;
                    loadMenus(currentPage);
                }
            });

            $('.next-page').click(() => {
                if (currentPage < totalPages) {
                    currentPage++;
                    loadMenus(currentPage);
                }
            });

            loadMenus(currentPage);
        });
    </script>
</body>

</html>
