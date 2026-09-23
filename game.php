<?php
declare(strict_types=1);

require_once __DIR__ . '/api/models_lib.php';

// 获取全局模型配置
$config = getModelsConfig();
$availableModels = getAvailableModels();

$enemyModel = $config['enemy'] ?? 'random';

// 如果配置为 random，从敌人目录中随机选一个
if ($enemyModel === 'random' && !empty($availableModels['enemies'])) {
    $randomIndex = array_rand($availableModels['enemies']);
    $enemyModel = $availableModels['enemies'][$randomIndex];
}

$enemyModelPath = 'assets/models/enemies/' . $enemyModel;
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>AR Game Shooter</title>
    <link rel="stylesheet" href="assets/css/game.css">
    <!-- 引入 A-Frame 和 MindAR -->
    <script src="https://aframe.io/releases/1.4.2/aframe.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/mind-ar@1.2.5/dist/mindar-image-aframe.prod.js"></script>
</head>
<body>

    <!-- AR 识别容器 -->
    <div id="ar-container">
        <a-scene mindar-image="imageTargetSrc: ./targets.mind?v=<?php echo time(); ?>; autoStart: true;" 
                 embedded 
                 color-space="sRGB" 
                 renderer="colorManagement: true, physicallyCorrectLights" 
                 vr-mode-ui="enabled: false" 
                 device-orientation-permission-ui="enabled: false">
            
            <!-- 资源预加载 -->
            <a-assets>
                <?php if (!empty($enemyModel) && file_exists(__DIR__ . '/' . $enemyModelPath)): ?>
                    <a-asset-item id="enemy-glb" src="<?php echo $enemyModelPath; ?>"></a-asset-item>
                <?php endif; ?>
            </a-assets>

            <!-- 场景基础灯光 -->
            <a-light type="ambient" intensity="1.2"></a-light>
            <a-light type="directional" position="0 10 5" intensity="1.5"></a-light>

            <a-camera position="0 0 0" look-controls="enabled: false"></a-camera>

            <!-- AR 图像识别目标锚点 -->
            <a-entity id="example-target" mindar-image-target="targetIndex: 0">
                <!-- 挂载怪物 3D 模型 -->
                <?php if (!empty($enemyModel) && file_exists(__DIR__ . '/' . $enemyModelPath)): ?>
                    <a-gltf-model id="enemy-model" 
                                  src="#enemy-glb" 
                                  position="0 0 0" 
                                  scale="0.3 0.3 0.3" 
                                  rotation="0 0 0">
                    </a-gltf-model>
                <?php else: ?>
                    <!-- 如果没有上传 GLB 模型，使用红色方块作为备用怪物测试 -->
                    <a-box position="0 0.2 0" material="color: red;" scale="0.3 0.3 0.3"></a-box>
                <?php endif; ?>
            </a-entity>

        </a-scene>
    </div>

    <!-- 准星 -->
    <div id="crosshair"></div>

    <!-- HUD 游戏界面 -->
    <div id="hud">
        <div class="hud-top">
            <div class="hud-stats-item">
                <span class="hud-stats-label">SCORE</span>
                <span id="score-val" class="hud-stats-val">0</span>
            </div>
            <div class="hud-stats-item">
                <span class="hud-stats-label">WAVE</span>
                <span id="wave-val" class="hud-stats-val">1</span>
            </div>
            <div class="hud-stats-item">
                <span class="hud-stats-label">HEALTH</span>
                <span id="hp-val" class="hud-stats-val hp">5</span>
            </div>
        </div>

        <div id="status-text">SEARCHING TARGET...</div>

        <div class="controls">
            <div class="ammo-box">
                <div class="hud-stats-label">AMMO</div>
                <div id="ammo-val" class="ammo-val">30/30</div>
            </div>
            <button id="btn-reload" class="action-btn">RELOAD</button>
            <button id="btn-fire" class="action-btn">FIRE</button>
        </div>
    </div>

    <script>
        // 绑定 AR 识别到目标图后的显示/隐藏状态
        const targetEntity = document.querySelector('#example-target');
        const statusText = document.querySelector('#status-text');

        targetEntity.addEventListener("targetFound", event => {
            statusText.innerText = "TARGET LOCKED!";
            statusText.style.color = "#00f0ff";
        });

        targetEntity.addEventListener("targetLost", event => {
            statusText.innerText = "SEARCHING TARGET...";
            statusText.style.color = "var(--primary)";
        });
    </script>
</body>
</html>