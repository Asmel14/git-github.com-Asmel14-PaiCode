<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/Auth/AuthService.php';

AuthService::requireAuth('login.php');
AuthService::startSession();

header('Content-Type: application/json; charset=UTF-8');

function respond(int $status, bool $success, string $message, array $extra = []): void
{
    http_response_code($status);
    echo json_encode(array_merge([
        'success' => $success,
        'message' => $message,
    ], $extra), JSON_UNESCAPED_UNICODE);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    respond(405, false, 'Metodo no permitido.');
}

$csrf = $_POST['_csrf'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
$csrfToken = is_string($csrf) ? $csrf : null;
if (!AuthService::verifyCsrfToken($csrfToken)) {
    respond(403, false, 'Token CSRF invalido.');
}

if (!isset($_FILES['logo']) || !is_array($_FILES['logo'])) {
    respond(400, false, 'Debes seleccionar una imagen.');
}

$logo = $_FILES['logo'];
$errorCode = (int) ($logo['error'] ?? UPLOAD_ERR_NO_FILE);
if ($errorCode !== UPLOAD_ERR_OK) {
    respond(400, false, 'Error al subir la imagen.');
}

$tmpFile = (string) ($logo['tmp_name'] ?? '');
if ($tmpFile === '' || !is_uploaded_file($tmpFile)) {
    respond(400, false, 'Archivo de subida no valido.');
}

$maxBytes = 2 * 1024 * 1024;
$fileSize = (int) ($logo['size'] ?? 0);
if ($fileSize <= 0 || $fileSize > $maxBytes) {
    respond(400, false, 'La imagen debe pesar maximo 2 MB.');
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = $finfo !== false ? (string) finfo_file($finfo, $tmpFile) : '';
if ($finfo !== false) {
    finfo_close($finfo);
}

$allowedMimes = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp',
    'image/gif' => 'gif',
];

if (!array_key_exists($mime, $allowedMimes)) {
    respond(400, false, 'Formato no permitido. Usa JPG, PNG, WEBP o GIF.');
}

$publicRoot = realpath(__DIR__ . '/..');
if ($publicRoot === false) {
    respond(500, false, 'No se pudo resolver el directorio publico.');
}

$relativeDir = 'uploads/logos';
$uploadDir = $publicRoot . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'logos';
if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
    respond(500, false, 'No se pudo crear el directorio de logos.');
}

$extension = $allowedMimes[$mime];
$filename = 'logo_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
$destination = $uploadDir . DIRECTORY_SEPARATOR . $filename;

if (!move_uploaded_file($tmpFile, $destination)) {
    respond(500, false, 'No se pudo guardar la imagen en el servidor.');
}

$currentLogo = trim((string) ($_POST['current_logo'] ?? ''));
if ($currentLogo !== '') {
    $normalized = str_replace('\\', '/', $currentLogo);
    $normalized = ltrim($normalized, '/');

    if (preg_match('#^uploads/logos/[a-zA-Z0-9_.-]+$#', $normalized) === 1) {
        $previousPath = $publicRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $normalized);
        if (is_file($previousPath)) {
            @unlink($previousPath);
        }
    }
}

$storedPath = $relativeDir . '/' . $filename;
respond(200, true, 'Logo cargado correctamente.', ['path' => $storedPath]);
