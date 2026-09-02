<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class CargosModel extends BaseModel
{
    protected string $table = 'cargos';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByNombre(string $nombre): ?array
    {
        return $this->find(['nombre' => trim($nombre)]);
    }

    public function getActivos(): array
    {
        $sql = 'SELECT * FROM `cargos` WHERE `estado` = 1 ORDER BY `nombre` ASC';
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    public function getAllOrdered(): array
    {
        $sql = 'SELECT * FROM `cargos` ORDER BY `nombre` ASC, `id` DESC';
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    public function createCargo(array $data): int
    {
        $this->validateData($data);

        $nombre = trim((string) $data['nombre']);
        if ($this->getByNombre($nombre) !== null) {
            throw new InvalidArgumentException('Ya existe un cargo con ese nombre.');
        }

        $newId = $this->create($data);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el cargo.');
        }

        return $newId;
    }

    public function updateCargo(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El cargo indicado no existe.');
        }

        if (array_key_exists('nombre', $data)) {
            $existingByName = $this->getByNombre((string) $data['nombre']);
            if ($existingByName !== null && (int) $existingByName['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe un cargo con ese nombre.');
            }
        }

        return $this->update(['id' => $id], $data);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        if (!$isPartial) {
            if (!array_key_exists('nombre', $data) || trim((string) $data['nombre']) === '') {
                throw new InvalidArgumentException('El campo nombre es obligatorio.');
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

        if (array_key_exists('descripcion', $data) && $data['descripcion'] !== null) {
            if (mb_strlen(trim((string) $data['descripcion'])) > 255) {
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
}
