<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - จัดการภาพในเว็บไซต์</title>
    <link rel="stylesheet" href="<?= ROOT_URL ?>/css/dashboard.css">
    <link rel="stylesheet" href="<?= ROOT_URL ?>/css/dashboards.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>
<?php include_once "component/dashborad.php"; ?>
    <?php
    include_once "database.php";
    
    



    // ฟังก์ชันอัปโหลดไฟล์ภาพ
    function uploadImage($file)
    {
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = time() . '.' . $extension;
        $targetFile = SITE_IMAGES_DIR . $filename;
        move_uploaded_file($file['tmp_name'], $targetFile);
        return $targetFile;
    }

    // การเพิ่มภาพใหม่
    if (isset($_POST['add_image'])) {
        $title = $_POST['title'];
        $defaultUrl = $_POST['defaultUrl'];
        $imageUrl = uploadImage($_FILES['imageUrl']);

        $sql = "INSERT INTO site_images (title, image_url, default_url) VALUES ('$title', '$imageUrl', '$defaultUrl')";
        if ($conn->query($sql)) {
            echo "<script>Swal.fire('สำเร็จ!', 'เพิ่มภาพใหม่เรียบร้อยแล้ว.', 'success');</script>";
        } else {
            echo "<script>Swal.fire('เกิดข้อผิดพลาด!', 'ไม่สามารถเพิ่มภาพได้.', 'error');</script>";
        }
    }

    // การแก้ไขภาพ
    if (isset($_POST['edit_image'])) {
        $id = $_POST['imageId'];
        $title = $_POST['title'];
        $defaultUrl = $_POST['defaultUrl'];
        $imageUrl = !empty($_FILES['imageUrl']['name']) ? uploadImage($_FILES['imageUrl']) : $_POST['existing_image'];

        $sql = "UPDATE site_images SET title = '$title', image_url = '$imageUrl', default_url = '$defaultUrl' WHERE id = '$id'";
        if ($conn->query($sql)) {
            echo "<script>Swal.fire('สำเร็จ!', 'ภาพถูกอัปเดตแล้ว.', 'success');</script>";
        } else {
            echo "<script>Swal.fire('เกิดข้อผิดพลาด!', 'ไม่สามารถอัปเดตภาพได้.', 'error');</script>";
        }
    }

    // การลบภาพ
    if (isset($_POST['delete_image'])) {
        $id = $_POST['imageId'];
        $sql = "DELETE FROM site_images WHERE id = '$id'";
        if ($conn->query($sql)) {
            echo "<script>Swal.fire('ลบแล้ว!', 'ภาพนี้ถูกลบแล้ว.', 'success');</script>";
        } else {
            echo "<script>Swal.fire('เกิดข้อผิดพลาด!', 'ไม่สามารถลบภาพได้.', 'error');</script>";
        }
        
    }
    // การเคลียร์ค่า imageUrl
if (isset($_POST['clear_image'])) {
    $id = $_POST['imageId'];
    $sql = "UPDATE site_images SET image_url = NULL WHERE id = '$id'";
    if ($conn->query($sql)) {
        echo "<script>Swal.fire('สำเร็จ!', 'เคลียร์ URL ของภาพเรียบร้อยแล้ว.', 'success');</script>";
    } else {
        echo "<script>Swal.fire('เกิดข้อผิดพลาด!', 'ไม่สามารถเคลียร์ URL ของภาพได้.', 'error');</script>";
    }
}


    // ดึงข้อมูลภาพทั้งหมดจากฐานข้อมูล
    $result = $conn->query("SELECT * FROM site_images");
    ?>
    


    <div class="main-content">
        <header>
            <h1>จัดการภาพในเว็บไซต์</h1>
        </header>
        <section class="dashboard">
            <h2>ภาพที่ใช้ในเว็บไซต์
                <button class="Add" data-action="Add" onclick="openAddModal()">เพิ่ม</button>
            </h2>

            <table id="siteImagesTable" class="dashboard-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ชื่อภาพ</th>
                        <th>รูปภาพ</th>
                        <th>URL ค่าเริ่มต้น</th>
                        <th>แก้ไข</th>
                        <th>ลบ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['title'] ?></td>
                            <td><img src="<?= ROOT_URL ?>/<?= $row['image_url'] ?>" alt="Image" class="img-thumbnail" style="width: 38px; height: 38px;"></td>
                            <td><?= $row['default_url'] ?></td>
                            <td><button data-id="<?= $row['id'] ?>" data-title="<?= $row['title'] ?>" data-image="<?= $row['image_url'] ?>" data-defaulturl="<?= $row['default_url'] ?>" onclick="openEditModal(this)">🖉</button></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="imageId" value="<?= $row['id'] ?>">
                                    <button type="submit" name="delete_image" class="delete-button">🗑️</button>
                                    <button type="submit" name="clear_image" class="clear-button">Clear</button>
                                </form>
                            </td>

                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <!-- Modal แก้ไขภาพ -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>แก้ไขภาพ</h2>
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="imageId" name="imageId">
                    <label for="title">ชื่อภาพ:</label>
                    <input type="text" id="title" name="title" required>
                    <label for="imageUrl">รูปภาพ:</label>
                    <img id="pre_EditimageUrl" src="https://via.placeholder.com/150?text=Profile+Picture" alt="Profile Picture">
                    <input type="file" id="EditimageUrl" name="imageUrl" accept="image/*" onchange="previewImage('EditimageUrl')">
                    <input type="hidden" name="existing_image" id="existing_image">
                    <label for="defaultUrl">URL ค่าเริ่มต้น:</label>
                    <input type="text" id="defaultUrl" name="defaultUrl" required>
                    <button type="submit" name="edit_image">บันทึก</button>
                </form>
            </div>
        </div>

        <!-- Modal เพิ่มภาพ -->
        <div id="AddModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>เพิ่มภาพใหม่</h2>
                <form id="AddForm" method="POST" enctype="multipart/form-data">
                    <label for="title">ชื่อภาพ:</label>
                    <input type="text" id="title" name="title" required>
                    <label for="imageUrl">รูปภาพ:</label>
                    <img id="pre_AddimageUrl" src="https://via.placeholder.com/150?text=Profile+Picture" alt="Profile Picture">
                    <input type="file" id="AddimageUrl" name="imageUrl" accept="image/*" onchange="previewImage('AddimageUrl')">
                    <label for="defaultUrl">URL ค่าเริ่มต้น:</label>
                    <input type="text" id="defaultUrl" name="defaultUrl" required>
                    <button type="submit" name="add_image">บันทึก</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAddModal() {
            $('#AddModal').show();
        }

        function openEditModal(button) {
            const id = button.getAttribute('data-id');
            const title = button.getAttribute('data-title');
            const imageUrl = button.getAttribute('data-image');
            const defaultUrl = button.getAttribute('data-defaulturl');

            $('#imageId').val(id);
            $('#title').val(title);
            $('#pre_EditimageUrl').attr('src', imageUrl);
            $('#existing_image').val(imageUrl);
            $('#defaultUrl').val(defaultUrl);

            $('#editModal').show();
        }

        function previewImage(inputId) {
            const input = document.getElementById(inputId);
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById(`pre_${inputId}`);
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        $(document).ready(() => {
            $('.modal .close').on('click', () => {
                $('.modal').hide();
            });
        });
    </script>
</body>

</html>