<?php
require_once __DIR__ . '/../models/Seccion.php';
require_once __DIR__ . '/../../config/db.php';

$seccionModel = new Seccion($pdo);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'listar':
        $id_grado = isset($_GET['id_grado']) ? intval($_GET['id_grado']) : null;
        echo json_encode($seccionModel->listar($id_grado));
        break;
    case 'obtener':
        $id = intval($_GET['id_seccion'] ?? 0);
        echo json_encode($seccionModel->obtener($id));
        break;
    case 'crear':
        $id_grado = intval($_POST['id_grado'] ?? 0);
        $nombre = $_POST['nombre'] ?? '';
        $ok = $seccionModel->crear($id_grado, $nombre);
        echo json_encode(['exito'=>$ok]);
        break;
    case 'actualizar':
        $id = intval($_POST['id_seccion'] ?? 0);
        $id_grado = intval($_POST['id_grado'] ?? 0);
        $nombre = $_POST['nombre'] ?? '';
        $ok = $seccionModel->actualizar($id, $id_grado, $nombre);
        echo json_encode(['exito'=>$ok]);
        break;
    case 'eliminar':
        $id = intval($_POST['id_seccion'] ?? 0);
        $ok = $seccionModel->eliminar($id);
        echo json_encode(['exito'=>$ok]);
        break;
    default:
        echo json_encode(['error'=>'Acción no válida']);
}
