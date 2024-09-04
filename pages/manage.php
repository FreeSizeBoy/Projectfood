<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ร้านอาหาร</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>

<body>
<?php

include_once "component/dashborad.php"

?>
    <div class="main-content">
        <header>
            <h1>ยินดีต้อนรับสู่ Dashboard ของร้านอาหาร</h1>
        </header>
        <section class="dashboard">
            <h2>จัดการสมาชิก</h2>
            <table id="table" class="dashboard-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>รูป</th>
                        <th>ผู้ใช้งาน</th>
                        <th>ชื่อเล่น</th>
                        <th>เบอร์โทร</th>
                        <th>อีเมล</th>
                        <th>ตำแหน่ง</th>
                        <th>แก้ไข</th>
                        <th>ลบ</th>
                    </tr>
                </thead>
                <tbody>
                    


                </tbody>
            </table>

            <!-- Modal -->
            <div id="editModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>แก้ไขข้อมูลผู้ใช้งาน</h2>
                    <form id="editForm">
                        <input type="hidden" id="userId" name="userId">
                        <label for="username">ชื่อผู้ใช้งาน:</label>
                        <input type="text" id="username" name="username" required>
                        <label for="nickname">ชื่อเล่น:</label>
                        <input type="text" id="nickname" name="nickname" required>
                        <label for="tel">เบอร์โทร:</label>
                        <input type="text" id="tel" name="tel" required>
                        <label for="email">อีเมล:</label>
                        <input type="email" id="email" name="email" required>
                        <label for="role">ตำแหน่ง:</label>
                        <select id="role" name="role" required>
                            <option value="super_admin">Super Admin</option>
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                            <option value="student">Student</option>
                        </select>
                        <button type="submit">บันทึก</button>
                    </form>
                </div>
            </div>

            <div id="editpassword" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>แก้ไขข้อมูลผู้ใช้งาน</h2>
                    <form id="editnewpassword">
                        <input type="hidden" id="userId" name="userId">
                        <label for="text">ตั้งรหัสผ่านใหม่</label>
                        <input type="text" id="password" name="password">
                        <button type="submit">บันทึก</button>
                    </form>
                </div>
            </div>

        </section>

        <script>
            $(document).ready(() => {
                $.ajax({
                    url: '<?= ROOT_URL ?>/api/manage',
                    type: 'GET',
                    success: function(response) {
                        response = JSON.parse(response);
                        if (response.status) {
                            const users = response.data;
                            const table = $('#table');
                            const tbody = table.find('tbody');
                            users.forEach(user => {
                                tbody.append(`
                                    <tr>
                                        <td>${user.id}</td>
                                        <td><img src="<?= USER_UPLOAD_DIR ?>/${user.img_url}" alt="Profile" id="profile-img" class="img-thumbnail" style="width: 38px; height: 38px; cursor: pointer;"></td>
                                        <td>${user.username}<br><button data-id="${user.id}" data-action="password" class="edit-button">🖉</button></td>
                                        <td>${user.nickname}</td>
                                        <td>${user.tel}</td>
                                        <td>${user.email}</td>
                                        <td>${user.role}</td>
                                        <td><button data-id="${user.id}" data-action="Edit" class="edit-button">🖉</button></td>
                                        <td><button data-id="${user.id}" data-action="Delete" class="delete-button">🗑️</button></td>
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
                                    $('#userId').val(id);
                                    // ทำการดึงข้อมูลผู้ใช้งานและเติมข้อมูลในฟอร์ม
                                    $.ajax({
                                        url: `<?= ROOT_URL ?>/api/users/${id}`,
                                        type: 'GET',
                                        success: function(response) {
                                            console.log(response);
                                            response = JSON.parse(response);
                                            if (response.status) {
                                                const user = response.data.user;
                                                $('#username').val(user.username);
                                                $('#nickname').val(user.nickname);
                                                $('#tel').val(user.tel);
                                                $('#email').val(user.email);
                                                $('#role').val(user.role);
                                            } else {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: response.massage
                                                });
                                            }
                                        } 

                                        
                                    });
                                } else if (action === 'Delete') {
                                    // $.ajax({
                                    //     url: `<?= ROOT_URL ?>/api/users/${id}/delete`,
                                    //     type: 'POST',
                                    //     success: function(response) {
                                    //         response = JSON.parse(response);
                                    //         if (response.status) {
                                    //             Swal.fire({                                                  
                                    //                 icon: 'success',
                                    //                 title: response.massage,
                                    //                 showConfirmButton: false,
                                    //                 timer: 1500
                                    //             }).then(() => {
                                    //                 location.reload();
                                    //             });
                                    //         } else {
                                    //             Swal.fire({
                                    //                 icon: 'error',
                                    //                 title: response.massage
                                    //             });
                                    //         }
                                    //     }
                                    // });
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
                                    url: `<?= ROOT_URL ?>/api/users/${id}/delete`,
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
                                } else if (action === "password") {
                                    $('#editpassword').show();
                                    $('#userId').val(id);
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: response.message
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        Swal.fire({
                            icon: 'error',
                            title: 'An error occurred'
                        });
                    }
                });
            });

            $('.close').on('click', function() {
                $('#editModal').hide();
                $('#editpassword').hide();
                $('#editpassword').hide();
            });

            // ส่งข้อมูลที่แก้ไข
            $('#editForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: `<?= ROOT_URL ?>/api/users/${$('#userId').val()}/edit`,
                    type: 'POST',
                    data: $(this).serialize(),
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

            $('#editnewpassword').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: `<?= ROOT_URL ?>/api/users/${$('#userId').val()}/edit`,
                    type: 'POST',
                    data: $(this).serialize(),
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
        </script>

</body>

</html>