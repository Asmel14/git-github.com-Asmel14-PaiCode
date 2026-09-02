<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

header('Content-Type: text/html; charset=UTF-8');

$inscripcionId = isset($_GET['inscripcion']) ? (int) $_GET['inscripcion'] : 0;
$estudianteId = isset($_GET['estudiante']) ? (int) $_GET['estudiante'] : 0;
$sigerd = isset($_GET['sigerd']) ? trim((string) $_GET['sigerd']) : '';

$ok = false;
$error = '';
$row = null;

if ($inscripcionId <= 0 || $estudianteId <= 0 || $sigerd === '') {
    $error = 'Parametros de verificacion incompletos o invalidos.';
} else {
    try {
        $db = Database::getConnection();
        $sql = "SELECT 
                    i.id AS inscripcion_id,
                    i.fecha_inscripcion,
                    i.inscripcion_activa,
                    e.id AS estudiante_id,
                    e.id_sigerd,
                    e.primer_nombre,
                    e.segundo_nombre,
                    e.primer_apellido,
                    e.segundo_apellido,
                    a.nombre AS anio_nombre,
                    n.nivel AS nivel_nombre,
                    g.grado AS grado_nombre,
                    s.seccion AS seccion_nombre,
                    t.nombre AS tanda_nombre,
                    t.codigo AS tanda_codigo,
                    c.nombre_centro
                FROM inscripciones i
                INNER JOIN estudiantes e ON e.id = i.estudiante_id
                LEFT JOIN planificaciones_academicas p ON p.id = i.planificacion_academica_id
                LEFT JOIN anios_escolares a ON a.id = p.anio_escolar_id
                LEFT JOIN niveles n ON n.id = p.nivel_id
                LEFT JOIN grados g ON g.id = p.grado_id
                LEFT JOIN secciones s ON s.id = p.seccion_id
                LEFT JOIN tandas t ON t.id = p.tanda_id
                LEFT JOIN datos_centro_educativo c ON c.estado = 1
                WHERE i.id = :inscripcion_id
                  AND e.id = :estudiante_id
                  AND e.id_sigerd = :sigerd
                LIMIT 1";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':inscripcion_id', $inscripcionId, PDO::PARAM_INT);
        $stmt->bindValue(':estudiante_id', $estudianteId, PDO::PARAM_INT);
        $stmt->bindValue(':sigerd', $sigerd, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $ok = is_array($row);
        if (!$ok) {
            $error = 'No se encontro un carnet valido con los datos escaneados.';
        }
    } catch (Throwable $e) {
        $error = 'Error al verificar autenticidad. Intenta nuevamente.';
    }
}

function h(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function nombreCompleto(array $data): string
{
    $parts = [
        trim((string) ($data['primer_nombre'] ?? '')),
        trim((string) ($data['segundo_nombre'] ?? '')),
        trim((string) ($data['primer_apellido'] ?? '')),
        trim((string) ($data['segundo_apellido'] ?? '')),
    ];

    $parts = array_values(array_filter($parts, static function (string $v): bool {
        return $v !== '';
    }));

    return $parts === [] ? '-' : implode(' ', $parts);
}

function formatDate(?string $isoDate): string
{
    $value = trim((string) $isoDate);
    if (!preg_match('/^\\d{4}-\\d{2}-\\d{2}$/', $value)) {
        return '-';
    }

    $parts = explode('-', $value);
    return $parts[2] . '/' . $parts[1] . '/' . $parts[0];
}

$estadoCarnet = $ok
    ? ((int) ($row['inscripcion_activa'] ?? 0) === 1 ? 'ACTIVO' : 'INACTIVO')
    : 'INVALIDO';

$tanda = '-';
if ($ok) {
    $tandaNombre = trim((string) ($row['tanda_nombre'] ?? ''));
    $tandaCodigo = trim((string) ($row['tanda_codigo'] ?? ''));
    $tanda = $tandaNombre !== '' ? $tandaNombre : ($tandaCodigo !== '' ? $tandaCodigo : '-');
}

?><!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificacion de carnet</title>
    <style>
        :root {
            --ok-bg: #ecfdf5;
            --ok-fg: #065f46;
            --bad-bg: #fef2f2;
            --bad-fg: #991b1b;
            --card: #ffffff;
            --line: #dbe2ea;
            --text: #0f172a;
            --muted: #475569;
            --bg: linear-gradient(145deg, #f8fafc 0%, #e2e8f0 100%);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, sans-serif;
            color: var(--text);
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            width: 100%;
            max-width: 760px;
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }
        .head {
            padding: 16px 20px;
            background: #0f172a;
            color: #fff;
            font-weight: 700;
            letter-spacing: .2px;
        }
        .status {
            margin: 16px 20px 0;
            padding: 12px 14px;
            border-radius: 10px;
            font-weight: 700;
        }
        .status.ok { background: var(--ok-bg); color: var(--ok-fg); }
        .status.bad { background: var(--bad-bg); color: var(--bad-fg); }
        .content { padding: 16px 20px 20px; }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        th, td {
            border: 1px solid var(--line);
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }
        th { width: 36%; background: #f8fafc; font-weight: 700; }
        .error {
            color: var(--bad-fg);
            font-weight: 600;
            margin-top: 6px;
        }
        .foot {
            margin-top: 14px;
            color: var(--muted);
            font-size: 12px;
        }
    </style>
</head>
<body>
    <section class="card">
        <div class="head">Verificacion de autenticidad de carnet</div>
        <div class="status <?= $ok ? 'ok' : 'bad' ?>">
            <?= $ok ? 'CARNET VALIDO' : 'CARNET NO VALIDO' ?>
        </div>
        <div class="content">
            <?php if ($ok && is_array($row)): ?>
                <table>
                    <tr><th>Centro educativo</th><td><?= h((string) ($row['nombre_centro'] ?? 'Centro educativo')) ?></td></tr>
                    <tr><th>Estudiante</th><td><?= h(nombreCompleto($row)) ?></td></tr>
                    <tr><th>ID SIGERD</th><td><?= h((string) ($row['id_sigerd'] ?? '-')) ?></td></tr>
                    <tr><th>Ano escolar</th><td><?= h((string) ($row['anio_nombre'] ?? '-')) ?></td></tr>
                    <tr><th>Nivel</th><td><?= h((string) ($row['nivel_nombre'] ?? '-')) ?></td></tr>
                    <tr><th>Grado</th><td><?= h((string) ($row['grado_nombre'] ?? '-')) ?></td></tr>
                    <tr><th>Seccion</th><td><?= h((string) ($row['seccion_nombre'] ?? '-')) ?></td></tr>
                    <tr><th>Tanda</th><td><?= h($tanda) ?></td></tr>
                    <tr><th>Fecha inscripcion</th><td><?= h(formatDate((string) ($row['fecha_inscripcion'] ?? ''))) ?></td></tr>
                    <tr><th>Estado del carnet</th><td><?= h($estadoCarnet) ?></td></tr>
                </table>
            <?php else: ?>
                <div class="error"><?= h($error !== '' ? $error : 'No fue posible validar este carnet.') ?></div>
            <?php endif; ?>
            <div class="foot">
                Fecha de verificacion: <?= h(date('d/m/Y H:i:s')) ?>
            </div>
        </div>
    </section>
</body>
</html>
