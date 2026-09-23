<?php
declare(strict_types=1);

require_once __DIR__ . '/api/site_lib.php';

$gameUrl = detectBaseUrl() . '/game.php';
$targetMindExists = file_exists(__DIR__ . '/assets/targets/targets.mind');
$statusText = $targetMindExists ? '已编译 · AR 目标图准备就绪' : '未编译 · 请先上传图片并编译';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebAR 射击游戏 - 扫码即玩</title>
    <link rel="stylesheet" href="assets/css/game.css">
</head>
<body class="theme-dark">
    <header class="navbar">
        <div class="logo">ARGAME</div>
        <nav>
            <a href="index.php">首页</a>
            <a href="models.php">模型管理</a>
            <a href="compile-target.php">编译目标图</a>
        </nav>
    </header>

    <main class="hero-container">
        <h1>AR GAME SHOOTER</h1>
        <p class="subtitle">网页端 AR 图像追踪射击游戏</p>

        <!-- QR Code 扫码体验区 -->
        <div class="qr-card">
            <h3>📱 用手机扫描二维码开始游戏</h3>
            <p>请确保手机与电脑连入同一个 Wi-Fi 网络</p>
            <div class="qr-wrapper">
                <!-- 使用 API 动态生成游戏入口二维码 -->
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?php echo urlencode($gameUrl); ?>" alt="Game QR Code">
            </div>
            <p class="url-text"><?php echo htmlspecialchars($gameUrl); ?></p>
        </div>

        <div class="target-preview-box">
            <h3>识别目标图预览 (Target Image)</h3>
            <p>请将此图片打印或在另一台设备的屏幕上显示：</p>
            <img src="assets/targets/picture.jpg?v=<?php echo time(); ?>" alt="Target Preview" onerror="this.src='https://via.placeholder.com/300x300?text=No+Target+Image'">
        </div>

        <div class="action-buttons">
            <a href="compile-target.php" class="btn btn-secondary">重新上传/编译目标图</a>
        </div>
    </main>
</body>
</html>