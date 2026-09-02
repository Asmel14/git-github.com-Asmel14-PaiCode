<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/Auth/AuthService.php';

AuthService::startSession();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    header('Location: index.php');
    http_response_code(302);
    exit;
}

$csrf = $_POST['_csrf'] ?? null;
if (!AuthService::verifyCsrfToken(is_string($csrf) ? $csrf : null)) {
    header('Location: index.php');
    http_response_code(302);
    exit;
}

AuthService::logout();

header('Location: login.php');
http_response_code(302);
exit;
