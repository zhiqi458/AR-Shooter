// 获取后台配置并处理武器加载逻辑
const modelsConfigResp = await fetch('assets/config/models.json?v=' + Date.now());
const modelsConfig = await modelsConfigResp.json();

let selectedWeapon = modelsConfig.weapon || 'random';

// 如果配置为 random，则请求 API 读取武器目录随机获取一个
if (selectedWeapon === 'random') {
    const availableResp = await fetch('api/models.php'); // 或直接扫描目录
    const availableModels = await availableResp.json();
    if (availableModels.weapons && availableModels.weapons.length > 0) {
        const randomIndex = Math.floor(Math.random() * availableModels.weapons.length);
        selectedWeapon = availableModels.weapons[randomIndex];
    }
}

// 拼接武器路径并加载
const weaponPath = selectedWeapon !== 'random' 
    ? `assets/models/weapons/${selectedWeapon}` 
    : 'assets/models/weapons/fps-akm.glb';

await weapon.load(weaponPath);