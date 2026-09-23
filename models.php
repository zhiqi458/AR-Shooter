<?php
declare(strict_types=1);

require_once __DIR__ . '/api/models_lib.php';

// 处理上传
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['glb_file'])) {
    $file = $_FILES['glb_file'];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        die("上传失败，PHP错误代码：" . $file['error']);
    }
    
    if ($file['size'] > 40 * 1024 * 1024) {
        die("文件超过 40MB 限制！");
    }

    $category = $_POST['model_category'] ?? 'enemies';
    $targetSubDir = ($category === 'weapons') ? 'weapons' : 'enemies';
    $destinationDir = __DIR__ . '/assets/models/' . $targetSubDir;

    if (!is_dir($destinationDir)) {
        mkdir($destinationDir, 0777, true);
    }

    $filename = preg_replace('/[^a-zA-Z0-9_.-]/', '_', basename($file['name']));
    
    if (move_uploaded_file($file['tmp_name'], $destinationDir . '/' . $filename)) {
        header('Location: models.php?status=success');
        exit;
    } else {
        die("文件移动失败，请检查文件夹权限：" . $destinationDir);
    }
}

// 处理配置保存
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_save_config'])) {
    saveModelsConfig([
        'enemy' => $_POST['enemy_model'] ?? 'random',
        'weapon' => $_POST['weapon_model'] ?? 'random'
    ]);
    header('Location: models.php?msg=saved');
    exit;
}

$models = getAvailableModels();
$config = getModelsConfig();
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>3D 模型与武器配置</title>
    <link rel="stylesheet" href="assets/css/game.css">
</head>
<body class="theme-dark">
    <header class="navbar">
        <div class="logo">ARGAME</div>
        <nav><a href="index.php">Home</a> | <a href="game.php">Play</a></nav>
    </header>

    <main class="hero-container">
        <h2>模型配置管理</h2>
        
        <form method="POST" style="margin: 2rem 0;">
            <input type="hidden" name="action_save_config" value="1">
            
            <div style="margin-bottom: 1.5rem;">
                <label>👾 怪物模型选择：</label><br>
                <select name="enemy_model" style="padding: 0.5rem; width: 60%; margin-top: 0.5rem;">
                    <option value="random" <?php echo ($config['enemy'] === 'random') ? 'selected' : ''; ?>>Random (随机选择怪物)</option>
                    <?php foreach ($models['enemies'] as $m): ?>
                        <option value="<?php echo htmlspecialchars($m); ?>" <?php echo ($config['enemy'] === $m) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($m); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label>🔫 枪械武器模型选择：</label><br>
                <select name="weapon_model" style="padding: 0.5rem; width: 60%; margin-top: 0.5rem;">
                    <option value="random" <?php echo ($config['weapon'] === 'random') ? 'selected' : ''; ?>>Random (随机选择武器)</option>
                    <?php foreach ($models['weapons'] as $w): ?>
                        <option value="<?php echo htmlspecialchars($w); ?>" <?php echo ($config['weapon'] === $w) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($w); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">保存设置</button>
        </form>

        <hr style="width: 100%; border-color: rgba(255,255,255,0.1); margin: 2rem 0;">

        <h3>上传自定义 .GLB 模型</h3>
        <form method="POST" enctype="multipart/form-data" style="margin-top: 1rem;">
            <div style="margin-bottom: 1rem;">
                <label>选择模型类型：</label>
                <select name="model_category" style="padding: 0.4rem;">
                    <option value="enemies">怪物模型 (Enemies)</option>
                    <option value="weapons">武器模型 (Weapons)</option>
                </select>
            </div>

            <input type="file" name="glb_file" accept=".glb" required>
            <button type="submit" class="btn btn-secondary">上传 GLB</button>
        </form>
    </main>
</body>
</html>