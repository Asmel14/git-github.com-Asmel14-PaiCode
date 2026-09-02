<?php

declare(strict_types=1);

$pageTitle = $pageTitle ?? 'Admin';
$assetBase = $assetBase ?? '../assets/vendor/sb-admin-2';
$customCss = $customCss ?? '../assets/css/admin-custom.css';
$contentView = $contentView ?? '';
$currentView = $currentView ?? 'dashboard';
$authUser = isset($authUser) && is_array($authUser) ? $authUser : [];
$csrfToken = isset($csrfToken) && is_string($csrfToken) ? $csrfToken : '';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>

    <link href="<?= htmlspecialchars($assetBase, ENT_QUOTES, 'UTF-8') ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="<?= htmlspecialchars($assetBase, ENT_QUOTES, 'UTF-8') ?>/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= htmlspecialchars($customCss, ENT_QUOTES, 'UTF-8') ?>" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-school"></i>
            </div>
            <div class="sidebar-brand-text mx-3">PAI <sup>CODE</sup></div>
        </a>

        <hr class="sidebar-divider my-0">

        <li class="nav-item <?= $currentView === 'dashboard' ? 'active' : '' ?>">
            <a class="nav-link" href="index.php?view=dashboard">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <div class="sidebar-heading">Registro</div>

        <li class="nav-item <?= $currentView === 'inscripcion-estudiante' ? 'active' : '' ?>"><a class="nav-link" href="index.php?view=inscripcion-estudiante"><i class="fas fa-fw fa-user-graduate"></i><span>Inscripcion de estudiante</span></a></li>
        <li class="nav-item <?= $currentView === 'reinscripcion-estudiantes' ? 'active' : '' ?>"><a class="nav-link" href="index.php?view=reinscripcion-estudiantes"><i class="fas fa-fw fa-redo-alt"></i><span>Reinscripcion de estudiantes</span></a></li>
        <li class="nav-item <?= $currentView === 'ficha-personal' ? 'active' : '' ?>"><a class="nav-link" href="index.php?view=ficha-personal"><i class="fas fa-fw fa-id-badge"></i><span>Ficha de personal</span></a></li>
        <li class="nav-item <?= $currentView === 'condicion-final-estudiante' ? 'active' : '' ?>"><a class="nav-link" href="index.php?view=condicion-final-estudiante"><i class="fas fa-fw fa-clipboard-check"></i><span>Condicion final de estudiante</span></a></li>
        <li class="nav-item <?= $currentView === 'relacion-estudiantes' ? 'active' : '' ?>"><a class="nav-link" href="index.php?view=relacion-estudiantes"><i class="fas fa-fw fa-link"></i><span>Relacion de estudiantes</span></a></li>
        
        <div class="sidebar-heading">Planeacion academica</div>

        <li class="nav-item <?= $currentView === 'creacion-anio-escolar' ? 'active' : '' ?>"><a class="nav-link" href="index.php?view=creacion-anio-escolar"><i class="fas fa-fw fa-calendar-alt"></i><span>Creacion de ano escolar</span></a></li>
        <li class="nav-item <?= $currentView === 'niveles' ? 'active' : '' ?>"><a class="nav-link" href="index.php?view=niveles"><i class="fas fa-fw fa-layer-group"></i><span>Niveles</span></a></li>
        <li class="nav-item <?= $currentView === 'grados' ? 'active' : '' ?>"><a class="nav-link" href="index.php?view=grados"><i class="fas fa-fw fa-sitemap"></i><span>Grados</span></a></li>
        <li class="nav-item <?= $currentView === 'secciones' ? 'active' : '' ?>"><a class="nav-link" href="index.php?view=secciones"><i class="fas fa-fw fa-th-large"></i><span>Secciones</span></a></li>
        <li class="nav-item <?= $currentView === 'tandas' ? 'active' : '' ?>"><a class="nav-link" href="index.php?view=tandas"><i class="fas fa-fw fa-clock"></i><span>Tandas</span></a></li>

        <div class="sidebar-heading">Recursos humanos</div>

        <li class="nav-item <?= $currentView === 'relacion-empleados' ? 'active' : '' ?>"><a class="nav-link" href="index.php?view=relacion-empleados"><i class="fas fa-fw fa-users-cog"></i><span>Relacion de empleados</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-fw fa-file-invoice"></i><span>Nomina borrador</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-fw fa-file-signature"></i><span>Nomina aprobada</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-fw fa-money-check"></i><span>Nomina pagada</span></a></li>

        <div class="sidebar-heading">Financiero</div>

        <li class="nav-item <?= $currentView === 'pagos' ? 'active' : '' ?>"><a class="nav-link" href="index.php?view=pagos"><i class="fas fa-fw fa-hand-holding-usd"></i><span>Pagos</span></a></li>
        <li class="nav-item <?= $currentView === 'historial-pagos' ? 'active' : '' ?>"><a class="nav-link" href="index.php?view=historial-pagos"><i class="fas fa-fw fa-history"></i><span>Historial de pagos</span></a></li>
        <li class="nav-item <?= $currentView === 'cuentas-por-cobrar' ? 'active' : '' ?>"><a class="nav-link" href="index.php?view=cuentas-por-cobrar"><i class="fas fa-fw fa-file-invoice-dollar"></i><span>Cuentas por cobrar</span></a></li>

        <div class="sidebar-heading">Sistema</div>

        <li class="nav-item <?= $currentView === 'usuarios' ? 'active' : '' ?>"><a class="nav-link" href="index.php?view=usuarios"><i class="fas fa-fw fa-users"></i><span>Usuarios</span></a></li>
        <li class="nav-item <?= $currentView === 'configuracion-sistema' ? 'active' : '' ?>"><a class="nav-link" href="index.php?view=configuracion-sistema"><i class="fas fa-fw fa-cogs"></i><span>Configuracion del sistema</span></a></li>
        <li class="nav-item <?= $currentView === 'auditoria' ? 'active' : '' ?>"><a class="nav-link" href="index.php?view=auditoria"><i class="fas fa-fw fa-shield-alt"></i><span>Auditoria</span></a></li>

        <div class="sidebar-heading">Reportes</div>

        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-fw fa-chart-bar"></i><span>Reportes academicos</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-fw fa-chart-line"></i><span>Reportes financieros</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-fw fa-chart-pie"></i><span>Reportes de nomina</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-fw fa-user-shield"></i><span>Roles activos</span></a></li>

        <hr class="sidebar-divider d-none d-md-block">

        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <h1 class="h5 mb-0 text-gray-700 font-weight-bold"><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></h1>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item d-flex align-items-center mr-2">
                        <span class="topbar-user-name"><?= htmlspecialchars((string) ($authUser['nombre_completo'] ?? 'Admin'), ENT_QUOTES, 'UTF-8') ?></span>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <form method="post" action="logout.php" class="form-inline m-0 p-0">
                            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                            <button type="submit" class="logout-icon-btn" title="Cerrar sesion" aria-label="Cerrar sesion">
                                <i class="fas fa-sign-out-alt fa-sm"></i>
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>

            <div class="container-fluid">
                <?php if ($contentView !== '' && file_exists($contentView)) { ?>
                    <?php require $contentView; ?>
                <?php } else { ?>
                    <div class="alert alert-warning">No se encontro la vista solicitada.</div>
                <?php } ?>
            </div>
        </div>

        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>PAICODE &copy; <?= date('Y') ?></span>
                </div>
            </div>
        </footer>
    </div>
</div>

<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<script src="<?= htmlspecialchars($assetBase, ENT_QUOTES, 'UTF-8') ?>/vendor/jquery/jquery.min.js"></script>
<script src="<?= htmlspecialchars($assetBase, ENT_QUOTES, 'UTF-8') ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= htmlspecialchars($assetBase, ENT_QUOTES, 'UTF-8') ?>/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="<?= htmlspecialchars($assetBase, ENT_QUOTES, 'UTF-8') ?>/js/sb-admin-2.min.js"></script>
</body>
</html>
