<?php
class Seccion {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }
    public function listar($id_grado = null) {
        if ($id_grado) {
            $stmt = $this->pdo->prepare("SELECT * FROM seccion WHERE id_grado = ? ORDER BY nombre");
            $stmt->execute([$id_grado]);
            return $stmt->fetchAll();
        } else {
            $stmt = $this->pdo->query("SELECT * FROM seccion ORDER BY nombre");
            return $stmt->fetchAll();
        }
    }
    public function obtener($id_seccion) {
        $stmt = $this->pdo->prepare("SELECT * FROM seccion WHERE id_seccion = ?");
        $stmt->execute([$id_seccion]);
        return $stmt->fetch();
    }
    public function crear($id_grado, $nombre) {
        $stmt = $this->pdo->prepare("INSERT INTO seccion (id_grado, nombre) VALUES (?, ?)");
        return $stmt->execute([$id_grado, $nombre]);
    }
    public function actualizar($id_seccion, $id_grado, $nombre) {
        $stmt = $this->pdo->prepare("UPDATE seccion SET id_grado=?, nombre=? WHERE id_seccion=?");
        return $stmt->execute([$id_grado, $nombre, $id_seccion]);
    }
    public function eliminar($id_seccion) {
        $stmt = $this->pdo->prepare("DELETE FROM seccion WHERE id_seccion=?");
        return $stmt->execute([$id_seccion]);
    }
}
