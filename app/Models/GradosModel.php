<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class GradosModel extends BaseModel
{
    protected string $table = 'grados';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'grado',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByNombre(string $grado): ?array
    {
        return $this->find(['grado' => trim($grado)]);
    }

    public function getAllOrdered(int $limit = 500): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `grados` ORDER BY `grado` ASC, `id` ASC LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createGrado(array $data): int
    {
        $this->validateData($data);

        $payload = $this->normalizePayload($data);

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el grado.');
        }

        return $newId;
    }

    public function updateGrado(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El grado indicado no existe.');
        }

        $payload = $this->normalizePayload($data);
        return $this->update(['id' => $id], $payload);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        if (!$isPartial) {
            if (!array_key_exists('grado', $data) || trim((string) $data['grado']) === '') {
                throw new InvalidArgumentException('El campo grado es obligatorio.');
            }
        }

        if (array_key_exists('grado', $data)) {
            $grado = trim((string) $data['grado']);
            if ($grado === '') {
                throw new InvalidArgumentException('grado no puede estar vacio.');
            }
            if (mb_strlen($grado) > 100) {
                throw new InvalidArgumentException('grado no puede exceder 100 caracteres.');
            }
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;

        if (array_key_exists('grado', $payload)) {
            $payload['grado'] = trim((string) $payload['grado']);
        }

        return $payload;
    }

    private function normalizeLimit(int $limit): int
    {
        if ($limit < 1) {
            return 1;
        }

        if ($limit > 3000) {
            return 3000;
        }

        return $limit;
    }
}
