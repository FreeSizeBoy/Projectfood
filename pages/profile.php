<?php
$user = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขโปรไฟล์นักเรียน</title>
    <link rel="stylesheet" href="css/profile-edit.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <h1>แก้ไขโปรไฟล์นักเรียน</h1>
        <form data-form="profile">
            <div class="form-group">
                <img id="profileImage" src="<?= $_SESSION['userImage'] ?? 'https://via.placeholder.com/150?text=Profile+Picture' ?>" alt="Profile Picture">
                <input type="file" id="uploadImage" name="uploadImage" accept="image/*" onchange="previewImage()">
            </div>
            <div class="form-group">
                <label for="fname">ชื่อ:</label>
                <input type="text" id="fname" name="fname" value="" required>
            </div>
            <div class="form-group">
                <label for="lname">นามสกุล:</label>
                <input type="text" id="lname" name="lname" value="" required>
            </div>
            <div class="form-group">
                <label for="nickname">ชื่อเล่น:</label>
                <input type="text" id="nickname" name="nickname" value="">
            </div>
            <div class="form-group">
                <label for="student_id">รหัสนักเรียน:</label>
                <input type="text" id="student_id" name="student_id" value="">
            </div>
            <div class="form-group">
                <label for="email">อีเมล:</label>
                <input type="email" id="email" name="email" value="" required>
            </div>
            <div class="form-group">
                <label for="tel">เบอร์โทรศัพท์:</label>
                <input type="tel" id="tel" name="tel" value="">
            </div>
            <div class="form-group">
                <label for="dob">วันเกิด:</label>
                <input type="date" id="dob" name="dob" value="">
            </div>
            <div class="form-group">
                <label for="room">ห้องเรียน:</label>
                <input type="text" id="room" name="room" value="">
            </div>
            <div class="form-group">
                <button type="submit">บันทึกการเปลี่ยนแปลง</button>
            </div>
        </form>
    </div>

<script>
    $(document).ready(() => {
        $.ajax({
            url: '<?= ROOT_URL ?>/api/users/<?= $user ?>',
            type: 'GET',
            success: function(response) {
                console.log(response);
                response = JSON.parse(response);
                if (response.status) {
                    const user = response.data.user;
                    const form = $('[data-form="profile"]');
                    form.find('#fname').val(user.fname);
                    form.find('#lname').val(user.lname);
                    form.find('#nickname').val(user.nickname);
                    form.find('#student_id').val(user.student_id);
                    form.find('#email').val(user.email);
                    form.find('#tel').val(user.tel);
                    form.find('#dob').val(user.dob);
                    form.find('#room').val(user.room);
                    form.find('#profileImage').attr('src', user.img_url ? `<?= USER_UPLOAD_DIR ?>/${user.img_url}` : 'https://via.placeholder.com/150?text=Profile+Picture');
                }
            }
        });

        $('[data-form="profile"]').on('submit', (e) => {
            e.preventDefault();
            $('button[type="submit"]').prop('disabled', true);
            const data = new FormData(e.target);
            $.ajax({
                url: '<?= ROOT_URL ?>/api/users/<?= $user ?>/edit',
                type: 'POST',
                data: data,
                processData: false, 
                contentType: false,
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
                        $('button[type="submit"]').prop('disabled', false);
                        Swal.fire({
                            icon: 'error',
                            title: response.massage
                        });
                    }
                },
                error: function(response) {
                    $('button[type="submit"]').prop('disabled', false);
                    console.log(response);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด'
                    });
                }
            });
        });

        function previewImage() {
            const file = document.getElementById('uploadImage').files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileImage').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    });
</script>
</body>
</html>
