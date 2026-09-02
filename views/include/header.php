<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset(
        $pageTitle) ? htmlspecialchars($pageTitle) : 'Dashboard PAIDE'; ?></title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        /* ...estilos proporcionados por el usuario... */
        .dashboard-topbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 60px;
            background: #fff;
            z-index: 200;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            border-bottom: 1px solid #eaeaea;
        }
        .dashboard-topbar-content {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
        }
        .dashboard-main {
            position: absolute;
            top: 60px;
            left: 260px;
            right: 0;
            bottom: 50px;
            padding: 32px 32px 0 32px;
            min-height: calc(100vh - 110px);
            background: #f5f8fa;
            overflow-y: auto;
            z-index: 10;
        }
        @media (max-width: 900px) {
            .dashboard-main {
                left: 0;
                padding: 16px;
            }
        }
        .dashboard-sidebar {
            height: calc(100vh - 50px);
            overflow-y: auto;
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            background: #f8fafc;
            z-index: 100;
            box-shadow: 2px 0 8px rgba(0,0,0,0.07);
            border-right: 1px solid #eaeaea;
        }
        .sidebar-menu {
            padding-bottom: 40px;
        }
        .sidebar-menu-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #3578e5;
            padding: 18px 24px 10px 24px;
            letter-spacing: 1px;
        }
        .panel-group .panel {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 8px rgba(53,120,229,0.04);
            margin-bottom: 12px;
            background: #fff;
        }
        .panel-heading {
            background: #f5f8fa;
            border-bottom: 1px solid #eaeaea;
            padding: 0;
            border-radius: 10px 10px 0 0;
        }
        .panel-title a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            color: #3578e5;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            font-size: 0.97rem;
            border-radius: 10px 10px 0 0;
            transition: background 0.2s, color 0.2s;
        }
        .panel-title a:hover, .panel-title a.active {
            background: #eaf1fb;
            color: #2856a7;
        }
        .panel-collapse {
            background: #fff;
            border-radius: 0 0 10px 10px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s cubic-bezier(0.4,0,0.2,1);
        }
        .panel-collapse.in {
            max-height: 500px;
            overflow: visible;
            transition: max-height 0.45s cubic-bezier(0.4,0,0.2,1);
        }
        .list-group-item.menu-options {
            border: none;
            border-radius: 0;
            padding: 10px 28px;
            color: #444;
            font-size: 0.93rem;
            background: none;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            display: block;
            text-align: left;
        }
        .list-group-item.menu-options:hover {
            background: #f5f8fa;
            color: #3578e5;
        }
        @media (max-width: 900px) {
            .dashboard-sidebar {
                width: 100vw;
                position: relative;
                height: auto;
                max-height: none;
            }
        }
        body {
            min-height: 100vh;
            overflow-y: scroll;
            background: #f5f8fa;
        }
        .dashboard-user-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 48px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.10);
            border-radius: 8px;
            min-width: 210px;
            padding: 8px 0;
            z-index: 9999;
            list-style: none;
            margin: 0;
        }
        .dashboard-user-menu li {
            padding: 10px 22px;
            cursor: pointer;
            font-size: 1em;
            color: #222;
            transition: background 0.2s, color 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .dashboard-user-menu li.divider {
            border-top: 1px solid #eaeaea;
            margin: 6px 0;
            padding: 0;
        }
        .dashboard-user-menu li:hover:not(.divider) {
            background: #f5f8fa;
            color: #3578e5;
        }
        .dashboard-user {
            position: relative;
            display: inline-block;
        }
        .dashboard-user-btn {
            background: none;
            border: none;
            font-size: 1em;
            cursor: pointer;
            padding: 8px 18px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <nav class="dashboard-topbar">
        <div class="dashboard-topbar-content">
            <div class="dashboard-logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/Logo_UNESCO.svg" alt="Logo">
                <span class="dashboard-title">PAICODE</span>
            </div>
            <div class="dashboard-user">
                <button class="dashboard-user-btn" id="dashboard-user-btn">
                    <i class="fa fa-user"></i> <?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Usuario'); ?> <span class="fa fa-caret-down"></span>
                </button>
                <ul class="dashboard-user-menu" id="dashboard-user-menu">
                    <li><i class="fa fa-id-badge"></i> <?php
                        // Mostrar cédula
                        $cedula = '';
                        if (isset($_SESSION['id_usuario'])) {
                            $stmt = $pdo->prepare("SELECT p.cedula FROM usuario u JOIN persona p ON u.id_persona=p.id_persona WHERE u.id_usuario=?");
                            $stmt->execute([$_SESSION['id_usuario']]);
                            $cedula = $stmt->fetchColumn();
                        }
                        echo htmlspecialchars($cedula ?: '-');
                    ?></li>
                    <li><i class="fa fa-briefcase"></i> Perfil: <?php
                        // Mostrar roles
                        $roles = [];
                        if (isset($_SESSION['id_usuario'])) {
                            foreach ($pdo->query("SELECT r.nombre FROM usuario_rol ur JOIN rol r ON ur.id_rol=r.id_rol WHERE ur.id_usuario=".$_SESSION['id_usuario']) as $r) {
                                $roles[] = $r['nombre'];
                            }
                        }
                        echo $roles ? htmlspecialchars(implode(', ', $roles)) : '-';
                    ?></li>
                    <li class="divider"></li>
                    <li onclick="window.location.href='cambiar_contrasena.php'"><i class="fa fa-key"></i> Cambiar contraseña</li>
                    <li onclick="window.location.href='logout.php'"><i class="fa fa-sign-out"></i> Cerrar Sesión</li>
                </ul>
            </div>
        </div>
    </nav>
    <aside class="dashboard-sidebar">
        <div class="sidebar-menu">
            <div class="sidebar-menu-title">Menú principal</div>
            <div class="panel-group" id="accordion-menu">
                <!-- Registro -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a href="#menu-registro" class="accordion-toggle">
                                <span class="fa fa-users" style="font-size:1.3em; padding-right:10px;"></span> Registro
                            </a>
                        </h4>
                    </div>
                    <div id="menu-registro" class="panel-collapse collapse">
                        <div class="list-group">
                            <a href="#" class="list-group-item menu-options">Condición académica final</a>
                            <a href="#" class="list-group-item menu-options">Inscripción de estudiantes</a>
                            <a href="/paide/app/views/ficha_personal.php" class="list-group-item menu-options">Ficha de personal</a>
                            <a href="#" class="list-group-item menu-options">Reinscripción de estudiantes</a>
                            <a href="#" class="list-group-item menu-options">Relación de estudiantes</a>
                        </div>
                    </div>
                </div>
                <!-- Planeación académica -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a href="#menu-planeacion" class="accordion-toggle">
                                <span class="fa fa-graduation-cap" style="font-size:1.3em; padding-right:10px;"></span> Planeación académica
                            </a>
                        </h4>
                    </div>
                    <div id="menu-planeacion" class="panel-collapse collapse">
                        <div class="list-group">
                            <a href="/paide/app/views/servicio_listar.php" class="list-group-item menu-options">Servicios</a>
                            <a href="/paide/app/views/seccion_listar.php" class="list-group-item menu-options">Secciones</a>
                        </div>
                    </div>
                </div>
                <!-- Infraestructura física -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a href="#menu-infraestructura" class="accordion-toggle">
                                <span class="fa fa-home" style="font-size:1.3em; padding-right:10px;"></span> Infraestructura física
                            </a>
                        </h4>
                    </div>
                    <div id="menu-infraestructura" class="panel-collapse collapse">
                        <div class="list-group">
                            <a href="#" class="list-group-item menu-options">Planta física</a>
                        </div>
                    </div>
                </div>
                <!-- Reportes -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a href="#menu-reportes" class="accordion-toggle">
                                <span class="fa fa-file-text" style="font-size:1.3em; padding-right:10px;"></span> Reportes
                            </a>
                        </h4>
                    </div>
                    <div id="menu-reportes" class="panel-collapse collapse">
                        <div class="list-group">
                            <a href="#" class="list-group-item menu-options">Cantidad de puestos por centro educativo</a>
                            <a href="#" class="list-group-item menu-options">Características de planta física</a>
                            <a href="#" class="list-group-item menu-options">Certificado de estudio</a>
                            <a href="#" class="list-group-item menu-options">Condición académica final</a>
                            <a href="#" class="list-group-item menu-options">Ficha de estudiante</a>
                            <a href="#" class="list-group-item menu-options">Histórico de matrícula</a>
                            <a href="#" class="list-group-item menu-options">Matrícula por división territorial</a>
                            <a href="#" class="list-group-item menu-options">Matrícula por sexo</a>
                            <a href="#" class="list-group-item menu-options">Relación de estudiantes objeto de Pruebas Nacionales</a>
                            <a href="#" class="list-group-item menu-options">Relación de estudiantes por secciones</a>
                            <a href="#" class="list-group-item menu-options">Relación de personal por centro, según puesto</a>
                        </div>
                    </div>
                </div>
                <!-- Configuración -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a href="#menu-configuracion" class="accordion-toggle">
                                <span class="fa fa-cogs" style="font-size:1.3em; padding-right:10px;"></span> Configuración
                            </a>
                        </h4>
                    </div>
                    <div id="menu-configuracion" class="panel-collapse collapse">
                        <div class="list-group">
                            <a href="#" class="list-group-item menu-options">Imagen de bienvenida</a>
                            <a href="#" class="list-group-item menu-options">Configuración del sistema</a>
                            <a href="#" class="list-group-item menu-options">Gestión de usuarios</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Acordeón mejorado y dinámico
            document.addEventListener('DOMContentLoaded', function() {
                const toggles = document.querySelectorAll('.accordion-toggle');
                toggles.forEach(function(toggle) {
                    toggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (!target) return;
                        // Cierra otros paneles
                        document.querySelectorAll('.panel-collapse').forEach(function(panel) {
                            if (panel !== target) {
                                panel.classList.remove('in');
                                panel.style.maxHeight = null;
                            }
                        });
                        // Alterna el panel actual
                        target.classList.toggle('in');
                        if (target.classList.contains('in')) {
                            target.style.maxHeight = target.scrollHeight + 'px';
                        } else {
                            target.style.maxHeight = null;
                        }
                    });
                });
            });
        </script>
        <!-- Menú desplegable usuario -->
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                var btn = document.getElementById('dashboard-user-btn');
                var menu = document.getElementById('dashboard-user-menu');
                if(btn && menu) {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
                    });
                    document.addEventListener('click', function(e) {
                        if (!menu.contains(e.target) && e.target !== btn) {
                            menu.style.display = 'none';
                        }
                    });
                }
            });
        </script>
    </aside> 