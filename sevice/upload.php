<?php

function uploadFile($file, $uploadDir, $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'], $maxSize = 5000000)
{
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = time() . '.' . $extension;
    $targetFile = $uploadDir . $filename;

    if (!in_array($extension, $allowedExtensions)) {
        return [
            'status' => false,
            'massage' => 'ไฟล์ที่อัพโหลดไม่ใช่รูปภาพ'
        ];
    }

    if (!getimagesize($file['tmp_name'])) {
        return [
            'status' => false,
            'massage' => 'ไฟล์ที่อัพโหลดไม่ใช่รูปภาพ'
        ];
    }

    if ($file['size'] > $maxSize) {
        return [
            'status' => false,
            'massage' => 'ไฟล์มีขนาดใหญ่เกินไป'
        ];
    }

    if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
        return [
            'status' => false,
            'massage' => 'มีข้อผิดพลาดในการอัพโหลดไฟล์'
        ];
    }

    return [
        'status' => true,
        'filename' => $filename
    ];
}