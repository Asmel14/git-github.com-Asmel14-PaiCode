<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/Auth/AuthService.php';

AuthService::requireAuth('login.php');

$basePath = dirname(__DIR__);
$layoutFile = $basePath . '/../app/Views/layouts/admin.php';

$view = trim((string) ($_GET['view'] ?? 'dashboard'));

$viewMap = [
    'dashboard' => [
        'title' => 'Dashboard Administrativo',
        'file' => 'dashboard.php',
    ],
    'inscripcion-estudiante' => [
        'title' => 'Registro - Inscripcion de estudiante',
        'file' => 'inscripcion-estudiante.php',
    ],
    'relacion-estudiantes' => [
        'title' => 'Registro - Relacion de estudiantes inscritos',
        'file' => 'relacion-estudiantes.php',
    ],
    'reinscripcion-estudiantes' => [
        'title' => 'Registro - Reinscripcion de estudiantes',
        'file' => 'reinscripcion-estudiantes.php',
    ],
    'condicion-final-estudiante' => [
        'title' => 'Registro - Condicion final de estudiante',
        'file' => 'condicion-final-estudiante.php',
    ],
    'ficha-personal' => [
        'title' => 'Registro - Ficha de personal',
        'file' => 'ficha-personal.php',
    ],
    'relacion-empleados' => [
        'title' => 'Recursos humanos - Relacion de empleados',
        'file' => 'relacion-empleados.php',
    ],
    'usuarios' => [
        'title' => 'Sistema - Usuarios',
        'file' => 'usuarios.php',
    ],
    'configuracion-sistema' => [
        'title' => 'Sistema - Configuracion del sistema',
        'file' => 'configuracion-sistema.php',
    ],
    'auditoria' => [
        'title' => 'Sistema - Auditoria',
        'file' => 'auditoria.php',
    ],
    'creacion-anio-escolar' => [
        'title' => 'Planeacion academica - Creacion de ano escolar',
        'file' => 'creacion-anio-escolar.php',
    ],
    'niveles' => [
        'title' => 'Planeacion academica - Niveles',
        'file' => 'niveles.php',
    ],
    'grados' => [
        'title' => 'Planeacion academica - Grados',
        'file' => 'grados.php',
    ],
    'secciones' => [
        'title' => 'Planeacion academica - Secciones',
        'file' => 'secciones.php',
    ],
    'tandas' => [
        'title' => 'Planeacion academica - Tandas',
        'file' => 'tandas.php',
    ],
    'pagos' => [
        'title' => 'Financiero - Pagos',
        'file' => 'pagos.php',
    ],
    'historial-pagos' => [
        'title' => 'Financiero - Historial de pagos',
        'file' => 'historial-pagos.php',
    ],
    'cuentas-por-cobrar' => [
        'title' => 'Financiero - Cuentas por cobrar',
        'file' => 'cuentas-por-cobrar.php',
    ],
];

if (!isset($viewMap[$view])) {
    $view = 'dashboard';
}

$pageTitle = $viewMap[$view]['title'];
$contentView = $basePath . '/../app/Views/admin/' . $viewMap[$view]['file'];
$currentView = $view;
$authUser = AuthService::user();
$csrfToken = AuthService::csrfToken();

if (!file_exists($layoutFile)) {
    http_response_code(500);
    echo 'No se encontro el layout admin.';
    return;
}

$assetBase = '../assets/vendor/sb-admin-2';
$customCss = '../assets/css/admin-custom.css';

require $layoutFile;
