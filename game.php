<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>AR Game Shooter</title>
    <!-- 引入游戏全局 CSS 样式 -->
    <link rel="stylesheet" href="assets/css/game.css">
    <!-- 引入 A-Frame & MindAR JS 库 -->
    <script src="https://aframe.io/releases/1.4.2/aframe.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/mind-ar@1.2.5/dist/mindar-image-aframe.prod.js"></script>
</head>
<body>

    <!-- AR 识别渲染容器 -->
    <div id="ar-container">
        <a-scene mindar-image="imageTargetSrc: ./targets.mind; autoStart: true;" 
                 embedded 
                 color-space="sRGB" 
                 renderer="colorManagement: true, physicallyCorrectLights" 
                 vr-mode-ui="enabled: false" 
                 device-orientation-permission-ui="enabled: false">
            
            <a-camera position="0 0 0" look-controls="enabled: false"></a-camera>

            <!-- AR 追踪目标点 (匹配 targets.mind) -->
            <a-entity id="example-target" mindar-image-target="targetIndex: 0">
                <!-- 游戏怪物模型放置在追踪点内部 -->
            </a-entity>
        </a-scene>
    </div>

    <!-- 准星 -->
    <div id="crosshair"></div>

    <!-- 游戏 HUD 界面 (顶部/底部状态栏) -->
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

    <!-- Game Over 结算弹窗 (默认加上 hidden 类进行隐藏) -->
    <div id="game-over-modal" class="modal hidden" style="position: absolute; top: 0; left: 0; width: 100vw; height: 100vh; display: flex; justify-content: center; align-items: center; z-index: 100;">
        <div class="modal-card">
            <h2>GAME OVER</h2>
            <p class="hud-stats-label">FINAL SCORE</p>
            <h1 id="final-score" style="color: var(--primary); margin-bottom: 1rem;">0</h1>
            <button id="btn-restart" class="btn btn-primary" onclick="location.reload()">PLAY AGAIN</button>
        </div>
    </div>

</body>
</html>