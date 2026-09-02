<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class PermisosModel extends BaseModel
{
    protected string $table = 'permisos';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'modulo',
        'nombre',
        'codigo',
        'descripcion',
        'estado',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByCodigo(string $codigo): ?array
    {
        return $this->find(['codigo' => strtoupper(trim($codigo))]);
    }

    public function getByModuloNombre(string $modulo, string $nombre): ?array
    {
        return $this->find([
            'modulo' => trim($modulo),
            'nombre' => trim($nombre),
        ]);
    }

    public function getByModulo(string $modulo, bool $soloActivos = false, int $limit = 500): array
    {
        $moduloNorm = trim($modulo);
        if ($moduloNorm === '') {
            throw new InvalidArgumentException('modulo no puede estar vacio.');
        }

        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `permisos` WHERE `modulo` = :modulo';
        if ($soloActivos) {
            $sql .= ' AND `estado` = 1';
        }
        $sql .= ' ORDER BY `nombre` ASC, `id` DESC LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':modulo', $moduloNorm);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getActivos(int $limit = 1000): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `permisos` WHERE `estado` = 1 ORDER BY `modulo` ASC, `nombre` ASC LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getAllOrdered(int $limit = 2000): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `permisos` ORDER BY `modulo` ASC, `nombre` ASC, `id` DESC LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createPermiso(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        if ($this->getByCodigo((string) $payload['codigo']) !== null) {
            throw new InvalidArgumentException('Ya existe un permiso con ese codigo.');
        }

        $existsByModuloNombre = $this->getByModuloNombre((string) $payload['modulo'], (string) $payload['nombre']);
        if ($existsByModuloNombre !== null) {
            throw new InvalidArgumentException('Ya existe un permiso con ese nombre en el modulo indicado.');
        }

        if (!array_key_exists('estado', $payload)) {
            $payload['estado'] = 1;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el permiso.');
        }

        return $newId;
    }

    public function updatePermiso(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El permiso indicado no existe.');
        }

        $payload = $this->normalizePayload($data);

        if (array_key_exists('codigo', $payload)) {
            $existsByCode = $this->getByCodigo((string) $payload['codigo']);
            if ($existsByCode !== null && (int) $existsByCode['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe un permiso con ese codigo.');
            }
        }

        if (array_key_exists('modulo', $payload) || array_key_exists('nombre', $payload)) {
            $targetModulo = array_key_exists('modulo', $payload) ? (string) $payload['modulo'] : (string) $current['modulo'];
            $targetNombre = array_key_exists('nombre', $payload) ? (string) $payload['nombre'] : (string) $current['nombre'];
            $existsByModuloNombre = $this->getByModuloNombre($targetModulo, $targetNombre);
            if ($existsByModuloNombre !== null && (int) $existsByModuloNombre['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe otro permiso con ese nombre en el modulo indicado.');
            }
        }

        return $this->update(['id' => $id], $payload);
    }

    public function activarPermiso(int $id): int
    {
        return $this->setEstado($id, 1);
    }

    public function desactivarPermiso(int $id): int
    {
        return $this->setEstado($id, 0);
    }

    private function setEstado(int $id, int $estado): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El permiso indicado no existe.');
        }

        if ((int) ($current['estado'] ?? -1) === $estado) {
            return 0;
        }

        return $this->update(['id' => $id], ['estado' => $estado]);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['modulo', 'nombre', 'codigo'];
        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        if (array_key_exists('modulo', $data)) {
            $modulo = trim((string) $data['modulo']);
            if ($modulo === '') {
                throw new InvalidArgumentException('modulo no puede estar vacio.');
            }
            if (mb_strlen($modulo) > 100) {
                throw new InvalidArgumentException('modulo no puede exceder 100 caracteres.');
            }
        }

        if (array_key_exists('nombre', $data)) {
            $nombre = trim((string) $data['nombre']);
            if ($nombre === '') {
                throw new InvalidArgumentException('nombre no puede estar vacio.');
            }
            if (mb_strlen($nombre) > 150) {
                throw new InvalidArgumentException('nombre no puede exceder 150 caracteres.');
            }
        }

        if (array_key_exists('codigo', $data)) {
            $codigo = strtoupper(trim((string) $data['codigo']));
            if ($codigo === '') {
                throw new InvalidArgumentException('codigo no puede estar vacio.');
            }
            if (mb_strlen($codigo) > 100) {
                throw new InvalidArgumentException('codigo no puede exceder 100 caracteres.');
            }
        }

        if (array_key_exists('descripcion', $data) && $data['descripcion'] !== null) {
            $descripcion = trim((string) $data['descripcion']);
            if ($descripcion !== '' && mb_strlen($descripcion) > 255) {
                throw new InvalidArgumentException('descripcion no puede exceder 255 caracteres.');
            }
        }

        if (array_key_exists('estado', $data)) {
            $estado = (int) $data['estado'];
            if (!in_array($estado, [0, 1], true)) {
                throw new InvalidArgumentException('estado solo permite 0 o 1.');
            }
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;

        if (array_key_exists('modulo', $payload)) {
            $payload['modulo'] = trim((string) $payload['modulo']);
        }

        if (array_key_exists('nombre', $payload)) {
            $payload['nombre'] = trim((string) $payload['nombre']);
        }

        if (array_key_exists('codigo', $payload)) {
            $payload['codigo'] = strtoupper(trim((string) $payload['codigo']));
        }

        if (array_key_exists('descripcion', $payload) && $payload['descripcion'] !== null) {
            $desc = trim((string) $payload['descripcion']);
            $payload['descripcion'] = $desc === '' ? null : $desc;
        }

        if (array_key_exists('estado', $payload)) {
            $payload['estado'] = (int) $payload['estado'];
        }

        return $payload;
    }

    private function normalizeLimit(int $limit): int
    {
        if ($limit < 1) {
            return 1;
        }
        if ($limit > 5000) {
            return 5000;
        }

        return $limit;
    }
}
