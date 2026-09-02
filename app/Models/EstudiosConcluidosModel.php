<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class EstudiosConcluidosModel extends BaseModel
{
    protected string $table = 'estudios_concluidos';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'personal_id',
        'nivel_academico',
        'entidad',
        'titulo',
        'anio_inicio',
        'anio_fin',
        'numero_registro',
        'numero_folio',
        'pais',
        'ciudad',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByPersonalId(int $personalId, int $limit = 200): array
    {
        $this->validatePositiveId($personalId, 'personal_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `estudios_concluidos`
                WHERE `personal_id` = :personal_id
                ORDER BY `anio_fin` DESC, `anio_inicio` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':personal_id', $personalId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createEstudio(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el estudio concluido.');
        }

        return $newId;
    }

    public function updateEstudio(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El estudio concluido indicado no existe.');
        }

        $payload = $this->normalizePayload($data);
        return $this->update(['id' => $id], $payload);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        if (!$isPartial) {
            if (!array_key_exists('personal_id', $data) || (int) $data['personal_id'] <= 0) {
                throw new InvalidArgumentException('El campo personal_id es obligatorio y debe ser mayor que cero.');
            }
        }

        if (array_key_exists('personal_id', $data)) {
            $this->validatePositiveId((int) $data['personal_id'], 'personal_id');
        }

        $textRules = [
            'nivel_academico' => 150,
            'entidad' => 255,
            'titulo' => 255,
            'numero_registro' => 100,
            'numero_folio' => 100,
            'pais' => 100,
            'ciudad' => 100,
        ];

        foreach ($textRules as $field => $maxLength) {
            if (!array_key_exists($field, $data) || $data[$field] === null) {
                continue;
            }

            $value = trim((string) $data[$field]);
            if ($value !== '' && mb_strlen($value) > $maxLength) {
                throw new InvalidArgumentException($field . ' no puede exceder ' . $maxLength . ' caracteres.');
            }
        }

        $anioInicio = null;
        $anioFin = null;

        if (array_key_exists('anio_inicio', $data) && $data['anio_inicio'] !== null && $data['anio_inicio'] !== '') {
            $anioInicio = $this->validateYear($data['anio_inicio'], 'anio_inicio');
        }

        if (array_key_exists('anio_fin', $data) && $data['anio_fin'] !== null && $data['anio_fin'] !== '') {
            $anioFin = $this->validateYear($data['anio_fin'], 'anio_fin');
        }

        if ($anioInicio !== null && $anioFin !== null && $anioInicio > $anioFin) {
            throw new InvalidArgumentException('anio_inicio no puede ser mayor que anio_fin.');
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;

        $textFields = [
            'nivel_academico',
            'entidad',
            'titulo',
            'numero_registro',
            'numero_folio',
            'pais',
            'ciudad',
        ];

        foreach ($textFields as $field) {
            if (!array_key_exists($field, $payload) || $payload[$field] === null) {
                continue;
            }

            $value = trim((string) $payload[$field]);
            $payload[$field] = $value === '' ? null : $value;
        }

        if (array_key_exists('personal_id', $payload)) {
            $payload['personal_id'] = (int) $payload['personal_id'];
        }

        foreach (['anio_inicio', 'anio_fin'] as $field) {
            if (!array_key_exists($field, $payload)) {
                continue;
            }

            if ($payload[$field] === null || $payload[$field] === '') {
                $payload[$field] = null;
                continue;
            }

            $payload[$field] = $this->validateYear($payload[$field], $field);
        }

        return $payload;
    }

    private function validateYear(mixed $value, string $field): int
    {
        $year = (int) $value;
        if ($year < 1900 || $year > 2100) {
            throw new InvalidArgumentException($field . ' debe estar entre 1900 y 2100.');
        }

        return $year;
    }

    private function validatePositiveId(int $id, string $field): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException($field . ' debe ser mayor que cero.');
        }
    }

    private function normalizeLimit(int $limit): int
    {
        if ($limit < 1) {
            return 1;
        }

        if ($limit > 2000) {
            return 2000;
        }

        return $limit;
    }
}
