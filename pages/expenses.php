<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้ารายจ่าย</title>
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
            <h1>หน้ารายจ่าย</h1>
        </header>

        <section class="dashboard">
            <h2>จัดการรายจ่าย <button class="Add" onclick="openExpenseModal()">เพิ่ม</button></h2>
            <table class="dashboard-table" id="expenseTable">
                <thead>
                    <tr>
                        <th>ID ร้าน</th>
                        <th>วันที่</th>
                        <th>รายการ</th>
                        <th>ค่าใช้จ่าย (บาท)</th>
                        <th>แก้ไข</th>
                        <th>ลบ</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be added dynamically here -->
                </tbody>
            </table>
        </section>

        <!-- Modal for Adding New Expense -->
        <div id="expenseModal" class="modal">

            <div class="modal-content">
                <span class="close" onclick="closeExpenseModal()">&times;</span>
                <h3>เพิ่มรายการรายจ่ายใหม่</h3>
                <form id="expenseForm">
                    <label for="">ID:ร้าน</label>
                    <input type="text" id="shop_id" <?php if ($shop_id) echo 'readonly'; ?>>
                    <label for="">รายการ</label>
                    <input type="text" id="note" placeholder="รายการ" required>
                    <label for="">ค่าใช้จ่าย</label>
                    <input type="number" id="priceout" placeholder="ค่าใช้จ่าย (บาท)" required>
                    <button class="submit-exp" type="button" onclick="addExpense()">บันทึก</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Open and Close Modal
        // Open and Close Modal
        function openExpenseModal(id = null, shop_id = '', note = '', priceout = '') {
            $('#expenseModal').show();
            if (id) {
                $('#expenseForm').data('id', id);
                $('#shop_id').val(shop_id).prop('readonly', true); // Set shop_id value and make readonly
                $('#note').val(note);
                $('#priceout').val(priceout);
                $('.submit-exp').text('บันทึกการแก้ไข');
            } else {
                $('#expenseForm').removeData('id');
                $('#expenseForm')[0].reset();
                const shopId = '<?= $shop_id ?>'; // Get shop_id value
                if (shopId) {
                    $('#shop_id').val(shopId).prop('readonly', true); // Set value and make readonly if shop_id is not null
                } else {
                    $('#shop_id').val('').prop('readonly', false); // Make it editable if shop_id is null
                }
                $('.submit-exp').text('บันทึก');
            }
        }



        function closeExpenseModal() {
            $('#expenseModal').hide();
        }

        function addExpense() {
            const shop_id = $('#shop_id').val();
            const note = $('#note').val();
            const priceout = $('#priceout').val();
            const id = $('#expenseForm').data('id');

            if (shop_id && note && priceout) {
                const url = id ? `<?= ROOT_URL ?>/api/expenses/${id}/edit` : '<?= ROOT_URL ?>/api/expenses/create';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        shop_id: shop_id,
                        note: note,
                        priceout: priceout
                    },
                    success: function(response) {
                        response = JSON.parse(response);

                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: id ? 'แก้ไขสำเร็จ' : 'บันทึกสำเร็จ',
                                text: id ? 'แก้ไขรายการเรียบร้อยแล้ว!' : 'บันทึกรายจ่ายเรียบร้อยแล้ว!'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: response.message || 'ไม่สามารถดำเนินการได้!'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้!'
                        });
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อมูลไม่ครบถ้วน',
                    text: 'กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน!'
                });
            }
        }

        function deleteExpense(id) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: 'คุณต้องการลบรายการนี้!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `<?= ROOT_URL ?>/api/expenses/${id}/delete`,
                        type: 'POST',
                        success: function(response) {
                            response = JSON.parse(response);

                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'ลบสำเร็จ',
                                    text: 'ลบรายการเรียบร้อยแล้ว!'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    text: response.message || 'ไม่สามารถลบรายการได้!'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้!'
                            });
                        }
                    });
                }
            });
        }

        $(document).ready(() => {
    // Fetch existing expenses from the server
    $.ajax({
        url: '<?= ROOT_URL ?>/api/expenses<?= $role == 'admin' ? "?shop_id=$shop_id" : "" ?>',
        type: 'GET',
        success: function(response) {
            response = JSON.parse(response);
            if (response.status) {
                const expenses = response.data;
                const tbody = $('#expenseTable').find('tbody');
                expenses.forEach(expense => {
                    tbody.append(`
                        <tr>
                            <td>${expense.shop_id}</td>
                            <td>${expense.createdAt}</td>
                            <td>${expense.note}</td>
                            <td>${expense.priceout}</td>
                            <td><button data-id="${expense.id}" data-shop_id="${expense.shop_id}" data-action="Edit" class="edit-button" onclick="openExpenseModal(${expense.id}, '${expense.shop_id}', '${expense.note}', '${expense.priceout}')">🖉</button></td>
                            <td><button data-id="${expense.id}" data-action="Delete" class="delete-button" onclick="deleteExpense(${expense.id})">🗑️</button></td>
                        </tr>
                    `);
                });
            }
        }
    });
});

    </script>


</body>

</html>