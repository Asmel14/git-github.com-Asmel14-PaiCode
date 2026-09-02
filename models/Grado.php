<?php
class Grado {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }
    public function listar($id_servicio = null) {
        if ($id_servicio) {
            $stmt = $this->pdo->prepare("SELECT * FROM grado WHERE id_servicio = ? ORDER BY nombre");
            $stmt->execute([$id_servicio]);
            return $stmt->fetchAll();
        } else {
            $stmt = $this->pdo->query("SELECT * FROM grado ORDER BY nombre");
            return $stmt->fetchAll();
        }
    }
    public function obtener($id_grado) {
        $stmt = $this->pdo->prepare("SELECT * FROM grado WHERE id_grado = ?");
        $stmt->execute([$id_grado]);
        return $stmt->fetch();
    }
    public function crear($id_servicio, $nombre) {
        $stmt = $this->pdo->prepare("INSERT INTO grado (id_servicio, nombre) VALUES (?, ?)");
        return $stmt->execute([$id_servicio, $nombre]);
    }
    public function actualizar($id_grado, $id_servicio, $nombre) {
        $stmt = $this->pdo->prepare("UPDATE grado SET id_servicio=?, nombre=? WHERE id_grado=?");
        return $stmt->execute([$id_servicio, $nombre, $id_grado]);
    }
    public function eliminar($id_grado) {
        $stmt = $this->pdo->prepare("DELETE FROM grado WHERE id_grado=?");
        return $stmt->execute([$id_grado]);
    }
}
