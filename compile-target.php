<?php
declare(strict_types=1);

$targetImagePath = 'assets/targets/picture.jpg';
$hasImage = file_exists(__DIR__ . '/' .$targetImagePath);
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MindAR 特征图编译</title>
    <link rel="stylesheet" href="assets/css/game.css">
</head>
<body class="theme-dark">

    <!-- 顶部导航栏 -->
    <header class="navbar">
        <div class="logo">ARGAME</div>
        <nav>
            <a href="index.php">首页</a>
            <a href="models.php">模型管理</a>
            <a href="compile-target.php" class="active">编译目标图</a>
        </nav>
    </header>

    <main class="hero-container">
        <h1>MindAR 特征图编译</h1>
        <p class="subtitle">正在为 WebAR 识别库转换 picture.jpg</p>

        <div class="target-preview-box">
            <?php if ($hasImage): ?>
                <img id="target-img" src="<?php echo $targetImagePath; ?>?v=<?php echo time(); ?>" alt="Target Image" crossorigin="anonymous">
            <?php else: ?>
                <p style="color: var(--accent);">未找到 assets/targets/picture.jpg 图片文件，请先添加！</p>
            <?php endif; ?>
        </div>

        <button id="compile-btn" class="btn btn-primary" <?php echo !$hasImage ? 'disabled' : ''; ?>>开始编译文件</button>
        <div id="status-text" style="margin-top: 1.5rem; display: inline-block;">等待开始...</div>
    </main>

    <script type="module">
        import { Compiler } from 'https://cdn.jsdelivr.net/npm/mind-ar@1.2.5/dist/mindar-image.prod.js';

        document.getElementById('compile-btn').addEventListener('click', async function () {
            const imgEl = document.getElementById('target-img');
            const statusEl = document.getElementById('status-text');
            const btn = this;

            if (!imgEl || !imgEl.complete || imgEl.naturalWidth === 0) {
                statusEl.innerText = "图片未成功加载，请确认 assets/targets/picture.jpg 存在！";
                return;
            }

            try {
                btn.disabled = true;
                statusEl.innerText = "正在初始化编译器...";

                const compiler = new Compiler();
                
                statusEl.innerText = "正在编译中，请稍候...";
                await compiler.compileImageTargets([imgEl], (progress) => {
                    statusEl.innerText = `编译进度: ${progress.toFixed(2)}%`;
                });

                const exportedBuffer = await compiler.exportData();
                const blob = new Blob([exportedBuffer], { type: 'application/octet-stream' });
                
                const downloadLink = document.createElement('a');
                downloadLink.href = URL.createObjectURL(blob);
                downloadLink.download = 'targets.mind';
                downloadLink.click();

                statusEl.innerText = "编译完成！已自动下载 targets.mind。";
                btn.disabled = false;
            } catch (err) {
                console.error(err);
                statusEl.innerText = "错误: " + err.message;
                btn.disabled = false;
            }
        });
    </script>
</body>
</html>