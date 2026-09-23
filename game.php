<?php
declare(strict_types=1);

require_once __DIR__ . '/api/models_lib.php';

// 获取全局模型配置
$config = getModelsConfig();
$availableModels = getAvailableModels();

// 获取配置的怪物与枪械模型
$enemyModel = $config['enemy'] ?? 'random';
if ($enemyModel === 'random' && !empty($availableModels['enemies'])) {
    $randomIndex = array_rand($availableModels['enemies']);
    $enemyModel = $availableModels['enemies'][$randomIndex];
}

$weaponModel = $config['weapon'] ?? '';

$enemyModelPath = 'assets/models/enemies/' . $enemyModel;
$weaponModelPath = 'assets/models/weapons/' . $weaponModel;
$hasWeapon = !empty($weaponModel) && file_exists(__DIR__ . '/' . $weaponModelPath);
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>AR Game Shooter</title>
    <link rel="stylesheet" href="assets/css/game.css">
    <script src="https://aframe.io/releases/1.4.2/aframe.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/mind-ar@1.2.5/dist/mindar-image-aframe.prod.js"></script>
</head>
<body>

    <!-- AR 画面容器 -->
    <div id="ar-container">
        <!-- 在 a-scene 中加上 colorManagement 与渲染设置 -->
        <a-scene mindar-image="imageTargetSrc: ./assets/targets/targets.mind?v=<?php echo time(); ?>; autoStart: true;" 
                embedded 
                color-space="sRGB" 
                renderer="colorManagement: true, physicallyCorrectLights: true, sortObjects: true" 
                vr-mode-ui="enabled: false" 
                device-orientation-permission-ui="enabled: false">

            <!-- 资源预加载 -->
            <a-assets>
                <?php if (!empty($enemyModel) && file_exists(__DIR__ . '/' . $enemyModelPath)): ?>
                    <a-asset-item id="enemy-glb" src="<?php echo $enemyModelPath; ?>"></a-asset-item>
                <?php endif; ?>

                <?php if ($hasWeapon): ?>
                    <a-asset-item id="weapon-glb" src="<?php echo $weaponModelPath; ?>"></a-asset-item>
                <?php endif; ?>
            </a-assets>

            <!-- 基础场景灯光 -->
            <a-light type="ambient" intensity="1.5"></a-light>
            <a-light type="directional" position="0 10 5" intensity="2"></a-light>

            <!-- 相机 + 持枪节点 (添加 render-order 强行置顶) -->
            <a-camera position="0 0 0" look-controls="enabled: false">
                
                <?php if ($hasWeapon): ?>
                    <!-- GLB 枪械模型 -->
                    <a-gltf-model id="player-weapon" 
                                src="#weapon-glb" 
                                position="0.2 -0.25 -0.5" 
                                rotation="0 180 0" 
                                scale="0.08 0.08 0.08">
                    </a-gltf-model>
                <?php else: ?>
                    <!-- 备用 3D 枪械（使用了 material="depthTest: false" 确保永远置顶不会消失） -->
                    <a-entity id="player-weapon-fallback" position="0.18 -0.18 -0.35" rotation="5 -10 0">
                        <!-- 枪管 -->
                        <a-cylinder position="0 0 -0.1" radius="0.018" height="0.3" rotation="90 0 0" 
                                    material="color: #222; metalness: 0.9; roughness: 0.2; depthTest: false;"></a-cylinder>
                        <!-- 枪身 -->
                        <a-box position="0 -0.02 0.02" depth="0.15" height="0.06" width="0.035" 
                            material="color: #00f0ff; metalness: 0.5; emissive: #00f0ff; emissiveIntensity: 0.5; depthTest: false;"></a-box>
                        <!-- 握把 -->
                        <a-box position="0 -0.07 0.05" depth="0.04" height="0.08" width="0.03" rotation="-15 0 0" 
                            material="color: #111; depthTest: false;"></a-box>
                    </a-entity>
                <?php endif; ?>

            </a-camera>

            <!-- AR 识别目标锚点 -->
            <a-entity id="example-target" mindar-image-target="targetIndex: 0">
                <?php if (!empty($enemyModel) && file_exists(__DIR__ . '/' . $enemyModelPath)): ?>
                    <a-gltf-model id="enemy-model" 
                                src="#enemy-glb" 
                                position="0 0 0" 
                                scale="0.3 0.3 0.3">
                    </a-gltf-model>
                <?php else: ?>
                    <a-box position="0 0.2 0" material="color: red;" scale="0.3 0.3 0.3"></a-box>
                <?php endif; ?>
            </a-entity>

        </a-scene>
    </div>

    <!-- 准星 -->
    <div id="crosshair"></div>

    <!-- HUD 界面 -->
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
        const targetEntity = document.querySelector('#example-target');
        const statusText = document.querySelector('#status-text');

        targetEntity.addEventListener("targetFound", () => {
            statusText.innerText = "TARGET LOCKED!";
            statusText.style.color = "#00f0ff";
        });

        targetEntity.addEventListener("targetLost", () => {
            statusText.innerText = "SEARCHING TARGET...";
            statusText.style.color = "var(--primary)";
        });
    </script>
</body>
</html>