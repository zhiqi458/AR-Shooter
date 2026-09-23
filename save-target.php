<?php
declare(strict_types=1);

// 接收前端在线编译导出的 targets.mind 二进制文件
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    if ($data !== false && strlen($data) > 0) {
        $targetPath = __DIR__ . '/assets/targets/targets.mind';
        if (file_put_contents($targetPath, $data)) {
            echo json_encode(['ok' => true, 'message' => 'targets.mind 保存成功']);
            exit;
        }
    }
}

http_response_code(400);
echo json_encode(['ok' => false, 'message' => '数据保存失败']);