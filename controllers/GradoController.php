<?php
require_once __DIR__ . '/../models/Grado.php';
require_once __DIR__ . '/../../config/db.php';

grados = new Grado($pdo);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'listar':
        $id_servicio = isset($_GET['id_servicio']) ? intval($_GET['id_servicio']) : null;
        echo json_encode($grados->listar($id_servicio));
        break;
    case 'obtener':
        $id = intval($_GET['id_grado'] ?? 0);
        echo json_encode($grados->obtener($id));
        break;
    case 'crear':
        $id_servicio = intval($_POST['id_servicio'] ?? 0);
        $nombre = $_POST['nombre'] ?? '';
        $ok = $grados->crear($id_servicio, $nombre);
        echo json_encode(['exito'=>$ok]);
        break;
    case 'actualizar':
        $id = intval($_POST['id_grado'] ?? 0);
        $id_servicio = intval($_POST['id_servicio'] ?? 0);
        $nombre = $_POST['nombre'] ?? '';
        $ok = $grados->actualizar($id, $id_servicio, $nombre);
        echo json_encode(['exito'=>$ok]);
        break;
    case 'eliminar':
        $id = intval($_POST['id_grado'] ?? 0);
        $ok = $grados->eliminar($id);
        echo json_encode(['exito'=>$ok]);
        break;
    default:
        echo json_encode(['error'=>'Acción no válida']);
}
