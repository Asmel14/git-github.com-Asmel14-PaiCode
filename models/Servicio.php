<?php
class Servicio {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }
    public function listar() {
        $stmt = $this->pdo->query("SELECT * FROM servicio ORDER BY nombre");
        return $stmt->fetchAll();
    }
    public function obtener($id_servicio) {
        $stmt = $this->pdo->prepare("SELECT * FROM servicio WHERE id_servicio = ?");
        $stmt->execute([$id_servicio]);
        return $stmt->fetch();
    }
    public function crear($nombre) {
        $stmt = $this->pdo->prepare("INSERT INTO servicio (nombre) VALUES (?)");
        return $stmt->execute([$nombre]);
    }
    public function actualizar($id_servicio, $nombre) {
        $stmt = $this->pdo->prepare("UPDATE servicio SET nombre=? WHERE id_servicio=?");
        return $stmt->execute([$nombre, $id_servicio]);
    }
    public function eliminar($id_servicio) {
        $stmt = $this->pdo->prepare("DELETE FROM servicio WHERE id_servicio=?");
        return $stmt->execute([$id_servicio]);
    }
}
