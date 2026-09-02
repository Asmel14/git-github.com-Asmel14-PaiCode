<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/Models/UsuariosModel.php';
require_once dirname(__DIR__) . '/Models/AuditoriaModel.php';

final class AuthService
{
    private const SESSION_KEY = 'paicode_auth_user';
    private const CSRF_KEY = 'paicode_csrf_token';

    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_name('PAICODESESSID');
        session_start([
            'cookie_httponly' => true,
            'cookie_samesite' => 'Lax',
            'use_strict_mode' => true,
        ]);
    }

    public static function csrfToken(): string
    {
        self::startSession();

        if (!isset($_SESSION[self::CSRF_KEY]) || !is_string($_SESSION[self::CSRF_KEY]) || $_SESSION[self::CSRF_KEY] === '') {
            $_SESSION[self::CSRF_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::CSRF_KEY];
    }

    public static function verifyCsrfToken(?string $token): bool
    {
        self::startSession();

        if (!is_string($token) || $token === '') {
            return false;
        }

        $current = $_SESSION[self::CSRF_KEY] ?? '';
        return is_string($current) && $current !== '' && hash_equals($current, $token);
    }

    public static function login(string $correo, string $contrasena): bool
    {
        self::startSession();

        $usuariosModel = new UsuariosModel();
        $usuario = $usuariosModel->autenticar($correo, $contrasena);
        if ($usuario === null) {
            return false;
        }

        session_regenerate_id(true);

        $_SESSION[self::SESSION_KEY] = [
            'id' => (int) ($usuario['id'] ?? 0),
            'nombre_completo' => (string) ($usuario['nombre_completo'] ?? ''),
            'correo' => (string) ($usuario['correo'] ?? ''),
            'estado' => (int) ($usuario['estado'] ?? 0),
        ];

        $usuariosModel->updateUltimoAcceso((int) $usuario['id']);
        self::logAuditEvent('LOGIN', 'usuarios', (int) $usuario['id'], [
            'correo' => (string) ($usuario['correo'] ?? ''),
            'nombre_completo' => (string) ($usuario['nombre_completo'] ?? ''),
        ]);

        return true;
    }

    public static function logout(): void
    {
        self::startSession();

        $user = self::user();
        if (is_array($user) && (int) ($user['id'] ?? 0) > 0) {
            self::logAuditEvent('LOGOUT', 'usuarios', (int) $user['id'], [
                'correo' => (string) ($user['correo'] ?? ''),
                'nombre_completo' => (string) ($user['nombre_completo'] ?? ''),
            ]);
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }

    private static function logAuditEvent(string $accion, string $tabla, int $registroId, array $context = []): void
    {
        try {
            $user = self::user();
            $usuarioId = is_array($user) ? (int) ($user['id'] ?? 0) : 0;
            if ($usuarioId <= 0) {
                $usuarioId = null;
            }

            $descripcion = '';
            foreach ($context as $key => $value) {
                $part = $key . '=' . (is_scalar($value) || $value === null ? (string) $value : json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                if ($descripcion !== '') {
                    $descripcion .= '; ';
                }
                $descripcion .= $part;
            }

            $auditoria = new AuditoriaModel();
            $auditoria->logEvent([
                'usuario_id' => $usuarioId,
                'modulo' => 'auth',
                'accion' => strtoupper(trim($accion)),
                'tabla' => $tabla,
                'registro_id' => $registroId > 0 ? $registroId : null,
                'descripcion' => $descripcion,
                'ip' => trim((string) ($_SERVER['REMOTE_ADDR'] ?? '')) ?: null,
            ]);
        } catch (Throwable $exception) {
            // No interrumpir login/logout si falla la auditoria.
        }
    }

    public static function user(): ?array
    {
        self::startSession();

        $user = $_SESSION[self::SESSION_KEY] ?? null;
        if (!is_array($user)) {
            return null;
        }

        return $user;
    }

    public static function check(): bool
    {
        $user = self::user();
        return is_array($user) && (int) ($user['id'] ?? 0) > 0 && (int) ($user['estado'] ?? 0) === 1;
    }

    public static function requireAuth(string $redirect = 'login.php'): void
    {
        if (self::check()) {
            return;
        }

        header('Location: ' . $redirect);
        http_response_code(302);
        exit;
    }
}
