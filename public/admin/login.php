<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/Auth/AuthService.php';

AuthService::startSession();

if (AuthService::check()) {
    header('Location: index.php');
    http_response_code(302);
    exit;
}

$error = '';
$correo = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $csrf = $_POST['_csrf'] ?? null;
    if (!AuthService::verifyCsrfToken(is_string($csrf) ? $csrf : null)) {
        $error = 'Token CSRF invalido. Intenta de nuevo.';
    } else {
        $correo = trim((string) ($_POST['correo'] ?? ''));
        $contrasena = (string) ($_POST['contrasena'] ?? '');

        if ($correo === '' || $contrasena === '') {
            $error = 'Debes completar correo y contrasena.';
        } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $error = 'El correo no tiene un formato valido.';
        } else {
            try {
                if (AuthService::login($correo, $contrasena)) {
                    header('Location: index.php');
                    http_response_code(302);
                    exit;
                }
                $error = 'Credenciales invalidas o usuario inactivo.';
            } catch (Throwable $exception) {
                $error = 'No fue posible iniciar sesion: ' . $exception->getMessage();
            }
        }
    }
}

$csrfToken = AuthService::csrfToken();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - PAICODE</title>

    <link href="../assets/vendor/sb-admin-2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="../assets/vendor/sb-admin-2/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6 col-md-8">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">PAICODE - Iniciar sesion</h1>
                        </div>

                        <?php if ($error !== '') { ?>
                            <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                        <?php } ?>

                        <form method="post" action="login.php" class="user">
                            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

                            <div class="form-group">
                                <input type="email" class="form-control form-control-user" name="correo" value="<?= htmlspecialchars($correo, ENT_QUOTES, 'UTF-8') ?>" placeholder="Correo electronico" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-user" name="contrasena" placeholder="Contrasena" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">Entrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../assets/vendor/sb-admin-2/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/sb-admin-2/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../assets/vendor/sb-admin-2/js/sb-admin-2.min.js"></script>
</body>
</html>
