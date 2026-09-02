<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class VacunasModel extends BaseModel
{
    protected string $table = 'vacunas';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'nombre',
        'estado',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByNombre(string $nombre): ?array
    {
        return $this->find(['nombre' => strtoupper(trim($nombre))]);
    }

    public function getActivos(int $limit = 1000): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `vacunas`
                WHERE `estado` = 1
                ORDER BY `nombre` ASC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getAllOrdered(int $limit = 2000): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `vacunas`
                ORDER BY `nombre` ASC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createVacuna(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        if ($this->getByNombre((string) $payload['nombre']) !== null) {
            throw new InvalidArgumentException('Ya existe una vacuna con ese nombre.');
        }

        if (!array_key_exists('estado', $payload)) {
            $payload['estado'] = 1;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear la vacuna.');
        }

        return $newId;
    }

    public function updateVacuna(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('La vacuna indicada no existe.');
        }

        $payload = $this->normalizePayload($data);

        if (array_key_exists('nombre', $payload)) {
            $exists = $this->getByNombre((string) $payload['nombre']);
            if ($exists !== null && (int) $exists['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe otra vacuna con ese nombre.');
            }
        }

        return $this->update(['id' => $id], $payload);
    }

    public function activarVacuna(int $id): int
    {
        return $this->setEstado($id, 1);
    }

    public function desactivarVacuna(int $id): int
    {
        return $this->setEstado($id, 0);
    }

    private function setEstado(int $id, int $estado): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('La vacuna indicada no existe.');
        }

        if ((int) ($current['estado'] ?? -1) === $estado) {
            return 0;
        }

        return $this->update(['id' => $id], ['estado' => $estado]);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        if (!$isPartial && !array_key_exists('nombre', $data)) {
            throw new InvalidArgumentException('El campo nombre es obligatorio.');
        }

        if (array_key_exists('nombre', $data)) {
            $nombre = strtoupper(trim((string) $data['nombre']));
            if ($nombre === '') {
                throw new InvalidArgumentException('nombre no puede estar vacio.');
            }
            if (mb_strlen($nombre) > 150) {
                throw new InvalidArgumentException('nombre no puede exceder 150 caracteres.');
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

        if (array_key_exists('nombre', $payload)) {
            $payload['nombre'] = strtoupper(trim((string) $payload['nombre']));
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
