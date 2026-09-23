<?php
declare(strict_types=1);

function getSiteConfig(): array {
    $configFile = __DIR__ . '/../assets/config/site.json';
    if (!file_exists($configFile)) {
        return ['publicBaseUrl' => ''];
    }
    $content = file_get_contents($configFile);
    return json_decode($content, true) ?? ['publicBaseUrl' => ''];
}

function saveSiteConfig(array $config): bool {
    $configFile = __DIR__ . '/../assets/config/site.json';
    return file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT)) !== false;
}

function detectBaseUrl(): string {
    $config = getSiteConfig();
    if (!empty($config['publicBaseUrl'])) {
        return rtrim($config['publicBaseUrl'], '/');
    }

    $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // 如果是在本地测试，自动获取局域网 IP
    if ($host === 'localhost' || $host === '127.0.0.1') {
        $localIp = gethostbyname(gethostname());
        if ($localIp !== gethostname()) {
            $host = $localIp;
        }
    }
    
    $dir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
    $dir = str_replace('\\', '/', $dir);
    if ($dir === '/') $dir = '';
    
    return $scheme . '://' . $host . $dir;
}