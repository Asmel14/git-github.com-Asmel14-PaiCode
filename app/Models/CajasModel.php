<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class CajasModel extends BaseModel
{
    protected string $table = 'cajas';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'ubicacion',
        'estado',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByCodigo(string $codigo): ?array
    {
        return $this->find(['codigo' => trim($codigo)]);
    }

    public function getByNombre(string $nombre): ?array
    {
        return $this->find(['nombre' => trim($nombre)]);
    }

    public function getActivas(): array
    {
        $sql = 'SELECT * FROM `cajas` WHERE `estado` = 1 ORDER BY `nombre` ASC';
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    public function getAllOrdered(): array
    {
        $sql = 'SELECT * FROM `cajas` ORDER BY `id` DESC';
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    public function createCaja(array $data): int
    {
        $this->validateData($data);

        $codigo = trim((string) $data['codigo']);
        $nombre = trim((string) $data['nombre']);

        if ($this->getByCodigo($codigo) !== null) {
            throw new InvalidArgumentException('Ya existe una caja con ese codigo.');
        }

        if ($this->getByNombre($nombre) !== null) {
            throw new InvalidArgumentException('Ya existe una caja con ese nombre.');
        }

        $newId = $this->create($data);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear la caja.');
        }

        return $newId;
    }

    public function updateCaja(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('La caja indicada no existe.');
        }

        if (array_key_exists('codigo', $data)) {
            $existingByCode = $this->getByCodigo((string) $data['codigo']);
            if ($existingByCode !== null && (int) $existingByCode['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe una caja con ese codigo.');
            }
        }

        if (array_key_exists('nombre', $data)) {
            $existingByName = $this->getByNombre((string) $data['nombre']);
            if ($existingByName !== null && (int) $existingByName['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe una caja con ese nombre.');
            }
        }

        return $this->update(['id' => $id], $data);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['nombre', 'codigo'];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data) || trim((string) $data[$field]) === '') {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        if (array_key_exists('nombre', $data)) {
            $nombre = trim((string) $data['nombre']);
            if ($nombre === '') {
                throw new InvalidArgumentException('nombre no puede estar vacio.');
            }
            if (mb_strlen($nombre) > 100) {
                throw new InvalidArgumentException('nombre no puede exceder 100 caracteres.');
            }
        }

        if (array_key_exists('codigo', $data)) {
            $codigo = trim((string) $data['codigo']);
            if ($codigo === '') {
                throw new InvalidArgumentException('codigo no puede estar vacio.');
            }
            if (mb_strlen($codigo) > 30) {
                throw new InvalidArgumentException('codigo no puede exceder 30 caracteres.');
            }
        }

        if (array_key_exists('descripcion', $data) && $data['descripcion'] !== null) {
            if (mb_strlen(trim((string) $data['descripcion'])) > 255) {
                throw new InvalidArgumentException('descripcion no puede exceder 255 caracteres.');
            }
        }

        if (array_key_exists('ubicacion', $data) && $data['ubicacion'] !== null) {
            if (mb_strlen(trim((string) $data['ubicacion'])) > 150) {
                throw new InvalidArgumentException('ubicacion no puede exceder 150 caracteres.');
            }
        }

        if (array_key_exists('estado', $data)) {
            $estado = (int) $data['estado'];
            if (!in_array($estado, [0, 1], true)) {
                throw new InvalidArgumentException('estado solo permite 0 o 1.');
            }
        }
    }
}
