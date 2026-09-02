<?php
require_once __DIR__ . '/../models/Servicio.php';
require_once __DIR__ . '/../../config/db.php';

$servicioModel = new Servicio($pdo);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'listar':
        echo json_encode($servicioModel->listar());
        break;
    case 'obtener':
        $id = intval($_GET['id_servicio'] ?? 0);
        echo json_encode($servicioModel->obtener($id));
        break;
    case 'crear':
        $nombre = $_POST['nombre'] ?? '';
        $ok = $servicioModel->crear($nombre);
        echo json_encode(['exito'=>$ok]);
        break;
    case 'actualizar':
        $id = intval($_POST['id_servicio'] ?? 0);
        $nombre = $_POST['nombre'] ?? '';
        $ok = $servicioModel->actualizar($id, $nombre);
        echo json_encode(['exito'=>$ok]);
        break;
    case 'eliminar':
        $id = intval($_POST['id_servicio'] ?? 0);
        $ok = $servicioModel->eliminar($id);
        echo json_encode(['exito'=>$ok]);
        break;
    default:
        echo json_encode(['error'=>'Acción no válida']);
}
