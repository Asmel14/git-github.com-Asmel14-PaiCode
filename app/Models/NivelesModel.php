<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class NivelesModel extends BaseModel
{
    protected string $table = 'niveles';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'nivel',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByNivel(string $nivel): ?array
    {
        return $this->find(['nivel' => trim($nivel)]);
    }

    public function getAllOrdered(int $limit = 500): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `niveles` ORDER BY `nivel` ASC, `id` DESC LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createNivel(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        if ($this->getByNivel((string) $payload['nivel']) !== null) {
            throw new InvalidArgumentException('Ya existe un nivel con ese nombre.');
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el nivel.');
        }

        return $newId;
    }

    public function updateNivel(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El nivel indicado no existe.');
        }

        $payload = $this->normalizePayload($data);

        if (array_key_exists('nivel', $payload)) {
            $existing = $this->getByNivel((string) $payload['nivel']);
            if ($existing !== null && (int) $existing['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe un nivel con ese nombre.');
            }
        }

        return $this->update(['id' => $id], $payload);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        if (!$isPartial && (!array_key_exists('nivel', $data) || trim((string) $data['nivel']) === '')) {
            throw new InvalidArgumentException('El campo nivel es obligatorio.');
        }

        if (array_key_exists('nivel', $data)) {
            $nivel = trim((string) $data['nivel']);
            if ($nivel === '') {
                throw new InvalidArgumentException('nivel no puede estar vacio.');
            }
            if (mb_strlen($nivel) > 100) {
                throw new InvalidArgumentException('nivel no puede exceder 100 caracteres.');
            }
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;
        if (array_key_exists('nivel', $payload)) {
            $payload['nivel'] = trim((string) $payload['nivel']);
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
