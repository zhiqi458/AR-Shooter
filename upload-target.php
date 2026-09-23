<?php
declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['target_image'])) {
    $file = $_FILES['target_image'];
    $maxSize = 12 * 1024 * 1024; // 12 MB

    if ($file['size'] > $maxSize) {
        die('错误：文件大小不能超过 12MB');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    $allowedTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

    if (!array_key_exists($mimeType, $allowedTypes)) {
        die('错误：仅支持上传 JPG, PNG 或 WEBP 格式图片');
    }

    // 关键修正：检查并自动创建 targets 目录
    $targetDir = __DIR__ . '/assets/targets';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $targetPath = $targetDir . '/picture.jpg';
    
    // 检查 GD 扩展支持
    if (!extension_loaded('gd')) {
        die('错误：PHP 未开启 GD 库扩展！');
    }

    // 将图片统一转换为标准 JPG 存储
    $image = match ($mimeType) {
        'image/jpeg' => imagecreatefromjpeg($file['tmp_name']),
        'image/png'  => imagecreatefrompng($file['tmp_name']),
        'image/webp' => imagecreatefromwebp($file['tmp_name']),
        default      => false,
    };

    if ($image !== false && imagejpeg($image, $targetPath, 90)) {
        imagedestroy($image);
        if (isset($_POST['auto_compile']) && $_POST['auto_compile'] === '1') {
            header('Location: compile-target.php');
            exit;
        }
        header('Location: index.php?msg=target_uploaded');
        exit;
    } else {
        die('错误：图片转换失败，请检查文件夹读写权限！');
    }
}
header('Location: index.php');