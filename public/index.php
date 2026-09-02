<?php

declare(strict_types=1);

// Punto de entrada de la aplicacion MVC.
error_reporting(E_ALL);
ini_set('display_errors', '1');

$basePath = dirname(__DIR__);
$routesFile = $basePath . '/routes/web.php';

$resource = trim((string) ($_GET['resource'] ?? ''));
$action = trim((string) ($_GET['action'] ?? ''));
$health = trim((string) ($_GET['health'] ?? ''));

if ($resource === '' && $action === '' && $health === '') {
    header('Location: admin/');
    http_response_code(302);
    return;
}

if (file_exists($routesFile)) {
    require_once $routesFile;
} else {
    http_response_code(500);
    echo 'No se encontro el archivo de rutas: routes/web.php';
}
