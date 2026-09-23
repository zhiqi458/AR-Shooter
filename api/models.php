<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/models_lib.php';

// 返回包含 enemies 和 weapons 两个数组的 JSON 格式数据
echo json_encode(getAvailableModels(), JSON_UNESCAPED_UNICODE);