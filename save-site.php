<?php
declare(strict_types=1);

require_once __DIR__ . '/api/site_lib.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = trim($_POST['public_url'] ?? '');
    saveSiteConfig(['publicBaseUrl' => $url]);
    header('Location: index.php?msg=site_config_saved');
    exit;
}
header('Location: index.php');