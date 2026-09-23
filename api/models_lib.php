<?php
declare(strict_types=1);

/**
 * 分别获取怪物与武器目录下的 GLB 模型列表
 */
function getAvailableModels(): array {
    $baseDir = __DIR__ . '/../assets/models';
    $enemiesDir = $baseDir . '/enemies';
    $weaponsDir = $baseDir . '/weapons';
    
    if (!is_dir($enemiesDir)) @mkdir($enemiesDir, 0777, true);
    if (!is_dir($weaponsDir)) @mkdir($weaponsDir, 0777, true);

    $enemyFiles = array_values(array_filter(scandir($enemiesDir) ?: [], function($file) use ($enemiesDir) {
        return is_file($enemiesDir . '/' . $file) && strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'glb';
    }));

    $weaponFiles = array_values(array_filter(scandir($weaponsDir) ?: [], function($file) use ($weaponsDir) {
        return is_file($weaponsDir . '/' . $file) && strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'glb';
    }));

    return [
        'enemies' => $enemyFiles,
        'weapons' => $weaponFiles
    ];
}

/**
 * 读取 models.json 配置文件
 */
function getModelsConfig(): array {
    $configFile = __DIR__ . '/../assets/config/models.json';
    
    if (!file_exists($configFile)) {
        $defaultConfig = [
            'enemy' => 'random',
            'weapon' => 'random'
        ];
        saveModelsConfig($defaultConfig);
        return $defaultConfig;
    }

    $content = file_get_contents($configFile);
    $data = json_decode($content, true);
    return [
        'enemy' => $data['enemy'] ?? 'random',
        'weapon' => $data['weapon'] ?? 'random'
    ];
}

/**
 * 保存配置到 models.json
 */
function saveModelsConfig(array $config): bool {
    $configDir = __DIR__ . '/../assets/config';
    if (!is_dir($configDir)) {
        @mkdir($configDir, 0777, true);
    }
    
    $configFile = $configDir . '/models.json';
    return file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT)) !== false;
}