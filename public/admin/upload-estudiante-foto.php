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

// Compatibilidad: permitir tanto "foto" (fetch actual) como "foto_estudiante" (nombre del input).
$fotoInput = null;
if (isset($_FILES['foto']) && is_array($_FILES['foto'])) {
    $fotoInput = $_FILES['foto'];
} elseif (isset($_FILES['foto_estudiante']) && is_array($_FILES['foto_estudiante'])) {
    $fotoInput = $_FILES['foto_estudiante'];
}

if (!is_array($fotoInput)) {
    respond(400, false, 'Debes seleccionar una foto.');
}

$foto = $fotoInput;
$errorCode = (int) ($foto['error'] ?? UPLOAD_ERR_NO_FILE);
if ($errorCode !== UPLOAD_ERR_OK) {
    $errorMessage = 'Error al subir la foto.';
    if ($errorCode === UPLOAD_ERR_NO_FILE) {
        $errorMessage = 'Debes seleccionar una foto.';
    } elseif ($errorCode === UPLOAD_ERR_INI_SIZE || $errorCode === UPLOAD_ERR_FORM_SIZE) {
        $errorMessage = 'La foto excede el tamano maximo permitido por el servidor.';
    } elseif ($errorCode === UPLOAD_ERR_PARTIAL) {
        $errorMessage = 'La foto se subio de forma parcial. Intenta de nuevo.';
    } elseif ($errorCode === UPLOAD_ERR_NO_TMP_DIR) {
        $errorMessage = 'Falta el directorio temporal de subida en el servidor.';
    } elseif ($errorCode === UPLOAD_ERR_CANT_WRITE) {
        $errorMessage = 'No se pudo escribir la foto en disco.';
    }

    respond(400, false, $errorMessage, ['upload_error_code' => $errorCode]);
}

$tmpFile = (string) ($foto['tmp_name'] ?? '');
if ($tmpFile === '' || !is_uploaded_file($tmpFile)) {
    respond(400, false, 'Archivo de subida no valido.');
}

$maxBytes = 8 * 1024 * 1024;
$fileSize = (int) ($foto['size'] ?? 0);
if ($fileSize <= 0 || $fileSize > $maxBytes) {
    respond(400, false, 'La foto debe pesar maximo 8 MB.');
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
];

if (!array_key_exists($mime, $allowedMimes)) {
    respond(400, false, 'Formato no permitido. Usa JPG, PNG o WEBP.');
}

$publicRoot = realpath(__DIR__ . '/..');
if ($publicRoot === false) {
    respond(500, false, 'No se pudo resolver el directorio publico.');
}

$relativeDir = 'uploads/estudiantes';
$uploadDir = $publicRoot . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'estudiantes';
if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
    respond(500, false, 'No se pudo crear el directorio de fotos de estudiantes.');
}

$extension = $allowedMimes[$mime];
$filename = 'estudiante_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
$destination = $uploadDir . DIRECTORY_SEPARATOR . $filename;

if (!move_uploaded_file($tmpFile, $destination)) {
    respond(500, false, 'No se pudo guardar la foto en el servidor.');
}

$storedPath = $relativeDir . '/' . $filename;
respond(200, true, 'Foto cargada correctamente.', ['path' => $storedPath]);
