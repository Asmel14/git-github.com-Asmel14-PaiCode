<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class PersonalDocumentosModel extends BaseModel
{
    protected string $table = 'personal_documentos';

    protected array $primaryKey = ['personal_id', 'documento_id'];

    protected array $fillable = [
        'personal_id',
        'documento_id',
        'tipo',
        'observaciones',
    ];

    public function getByPersonalAndDocumento(int $personalId, int $documentoId): ?array
    {
        $this->validatePositiveId($personalId, 'personal_id');
        $this->validatePositiveId($documentoId, 'documento_id');

        return $this->find([
            'personal_id' => $personalId,
            'documento_id' => $documentoId,
        ]);
    }

    public function getByPersonalId(int $personalId, int $limit = 500): array
    {
        $this->validatePositiveId($personalId, 'personal_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `personal_documentos`
                WHERE `personal_id` = :personal_id
                ORDER BY `created_at` DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':personal_id', $personalId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByDocumentoId(int $documentoId, int $limit = 500): array
    {
        $this->validatePositiveId($documentoId, 'documento_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `personal_documentos`
                WHERE `documento_id` = :documento_id
                ORDER BY `created_at` DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':documento_id', $documentoId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByTipo(string $tipo, int $limit = 500): array
    {
        $tipoNorm = trim($tipo);
        if ($tipoNorm === '') {
            throw new InvalidArgumentException('tipo no puede estar vacio.');
        }

        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `personal_documentos`
                WHERE `tipo` = :tipo
                ORDER BY `created_at` DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':tipo', $tipoNorm);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createRelacion(array $data): bool
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        $exists = $this->getByPersonalAndDocumento((int) $payload['personal_id'], (int) $payload['documento_id']);
        if ($exists !== null) {
            throw new InvalidArgumentException('Ya existe la relacion personal-documento indicada.');
        }

        $this->create($payload);
        return true;
    }

    public function updateRelacion(int $personalId, int $documentoId, array $data): int
    {
        $this->validatePositiveId($personalId, 'personal_id');
        $this->validatePositiveId($documentoId, 'documento_id');
        $this->validateData($data, true);

        $current = $this->getByPersonalAndDocumento($personalId, $documentoId);
        if ($current === null) {
            throw new InvalidArgumentException('La relacion personal-documento indicada no existe.');
        }

        $payload = $this->normalizePayload($data);

        if (array_key_exists('personal_id', $payload) || array_key_exists('documento_id', $payload)) {
            throw new InvalidArgumentException('No se permite modificar personal_id o documento_id en una relacion existente.');
        }

        return $this->update([
            'personal_id' => $personalId,
            'documento_id' => $documentoId,
        ], $payload);
    }

    public function deleteRelacion(int $personalId, int $documentoId): int
    {
        $this->validatePositiveId($personalId, 'personal_id');
        $this->validatePositiveId($documentoId, 'documento_id');

        return $this->delete([
            'personal_id' => $personalId,
            'documento_id' => $documentoId,
        ]);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['personal_id', 'documento_id', 'tipo'];
        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        foreach (['personal_id', 'documento_id'] as $field) {
            if (array_key_exists($field, $data)) {
                $this->validatePositiveId((int) $data[$field], $field);
            }
        }

        if (array_key_exists('tipo', $data)) {
            $tipo = trim((string) $data['tipo']);
            if ($tipo === '') {
                throw new InvalidArgumentException('tipo no puede estar vacio.');
            }
            if (mb_strlen($tipo) > 100) {
                throw new InvalidArgumentException('tipo no puede exceder 100 caracteres.');
            }
        }

        if (array_key_exists('observaciones', $data) && $data['observaciones'] !== null) {
            $obs = trim((string) $data['observaciones']);
            if ($obs !== '' && mb_strlen($obs) > 255) {
                throw new InvalidArgumentException('observaciones no puede exceder 255 caracteres.');
            }
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;

        foreach (['personal_id', 'documento_id'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = (int) $payload[$field];
            }
        }

        if (array_key_exists('tipo', $payload)) {
            $payload['tipo'] = trim((string) $payload['tipo']);
        }

        if (array_key_exists('observaciones', $payload) && $payload['observaciones'] !== null) {
            $obs = trim((string) $payload['observaciones']);
            $payload['observaciones'] = $obs === '' ? null : $obs;
        }

        return $payload;
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
        if ($limit > 3000) {
            return 3000;
        }

        return $limit;
    }
}
