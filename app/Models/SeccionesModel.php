<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class SeccionesModel extends BaseModel
{
    protected string $table = 'secciones';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'seccion',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getBySeccion(string $seccion): ?array
    {
        return $this->find(['seccion' => strtoupper(trim($seccion))]);
    }

    public function getAllOrdered(int $limit = 500): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `secciones` ORDER BY `seccion` ASC, `id` DESC LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createSeccion(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        if ($this->getBySeccion((string) $payload['seccion']) !== null) {
            throw new InvalidArgumentException('Ya existe una seccion con ese nombre.');
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear la seccion.');
        }

        return $newId;
    }

    public function updateSeccion(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('La seccion indicada no existe.');
        }

        $payload = $this->normalizePayload($data);

        if (array_key_exists('seccion', $payload)) {
            $exists = $this->getBySeccion((string) $payload['seccion']);
            if ($exists !== null && (int) $exists['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe otra seccion con ese nombre.');
            }
        }

        return $this->update(['id' => $id], $payload);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        if (!$isPartial && !array_key_exists('seccion', $data)) {
            throw new InvalidArgumentException('El campo seccion es obligatorio.');
        }

        if (array_key_exists('seccion', $data)) {
            $seccion = strtoupper(trim((string) $data['seccion']));
            if ($seccion === '') {
                throw new InvalidArgumentException('seccion no puede estar vacia.');
            }
            if (mb_strlen($seccion) > 50) {
                throw new InvalidArgumentException('seccion no puede exceder 50 caracteres.');
            }
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;
        if (array_key_exists('seccion', $payload)) {
            $payload['seccion'] = strtoupper(trim((string) $payload['seccion']));
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
