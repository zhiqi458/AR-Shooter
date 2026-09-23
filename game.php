<!-- 手机触控游戏 HUD -->
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
    
    <div id="crosshair"></div>

    <div class="controls">
        <button id="btn-reload" class="action-btn">RELOAD</button>
        <div class="ammo-box">
            <div style="font-size: 0.6rem; color: var(--text-dim);">AMMO</div>
            <div class="ammo-val"><span id="ammo-val">30</span><span style="font-size:0.8rem; color:var(--text-dim)">/30</span></div>
        </div>
        <button id="btn-fire" class="action-btn">FIRE</button>
    </div>
</div>

<!-- 美化版 Game Over 弹窗 -->
<div id="game-over-modal" class="modal hidden" style="position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:99;display:flex;justify-content:center;align-items:center;">
    <div class="modal-card">
        <h2>GAME OVER</h2>
        <p style="font-size:1.1rem; color:var(--text-dim); margin-bottom:0.5rem;">FINAL SCORE</p>
        <p id="final-score" style="font-family:'Orbitron'; font-size:2rem; color:var(--primary); margin-bottom:1rem;">0</p>
        
        <p style="font-size:1.1rem; color:var(--text-dim); margin-bottom:0.5rem;">WAVES SURVIVED</p>
        <p id="final-wave" style="font-family:'Orbitron'; font-size:1.5rem; color:var(--warning); margin-bottom:2rem;">1</p>
        
        <button id="btn-restart" class="btn btn-primary" style="width:100%;">PLAY AGAIN</button>
    </div>
</div>