<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class TarifasGradosModel extends BaseModel
{
    protected string $table = 'tarifas_grados';

    protected array $primaryKey = ['id'];

    private const JORNADA_MATUTINO = 'MATUTINO';
    private const JORNADA_VESPERTINO = 'VESPERTINO';

    protected array $fillable = [
        'tarifario_id',
        'nivel_id',
        'grado_id',
        'jornada',
        'tarifa_inscripcion',
        'mensualidad',
        'activo',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByTarifarioId(int $tarifarioId, int $limit = 500): array
    {
        $this->validatePositiveId($tarifarioId, 'tarifario_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `tarifas_grados`
                WHERE `tarifario_id` = :tarifario_id
                ORDER BY `nivel_id` ASC, `grado_id` ASC, `jornada` ASC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':tarifario_id', $tarifarioId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByNivelId(int $nivelId, int $limit = 500): array
    {
        $this->validatePositiveId($nivelId, 'nivel_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `tarifas_grados`
                WHERE `nivel_id` = :nivel_id
                ORDER BY `tarifario_id` DESC, `grado_id` ASC, `jornada` ASC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nivel_id', $nivelId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByGradoId(int $gradoId, int $limit = 500): array
    {
        $this->validatePositiveId($gradoId, 'grado_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `tarifas_grados`
                WHERE `grado_id` = :grado_id
                ORDER BY `tarifario_id` DESC, `nivel_id` ASC, `jornada` ASC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':grado_id', $gradoId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByUniqueKey(int $tarifarioId, int $nivelId, int $gradoId, string $jornada): ?array
    {
        $this->validatePositiveId($tarifarioId, 'tarifario_id');
        $this->validatePositiveId($nivelId, 'nivel_id');
        $this->validatePositiveId($gradoId, 'grado_id');

        $jornadaNorm = strtoupper(trim($jornada));
        $this->validateJornada($jornadaNorm);

        return $this->find([
            'tarifario_id' => $tarifarioId,
            'nivel_id' => $nivelId,
            'grado_id' => $gradoId,
            'jornada' => $jornadaNorm,
        ]);
    }

    public function getActivosByTarifario(int $tarifarioId, int $limit = 500): array
    {
        $this->validatePositiveId($tarifarioId, 'tarifario_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `tarifas_grados`
                WHERE `tarifario_id` = :tarifario_id
                  AND `activo` = 1
                ORDER BY `nivel_id` ASC, `grado_id` ASC, `jornada` ASC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':tarifario_id', $tarifarioId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createTarifa(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        $exists = $this->getByUniqueKey(
            (int) $payload['tarifario_id'],
            (int) $payload['nivel_id'],
            (int) $payload['grado_id'],
            (string) $payload['jornada']
        );
        if ($exists !== null) {
            throw new InvalidArgumentException('Ya existe una tarifa para la combinacion tarifario-nivel-grado-jornada.');
        }

        if (!array_key_exists('activo', $payload)) {
            $payload['activo'] = 1;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear la tarifa por grado.');
        }

        return $newId;
    }

    public function updateTarifa(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('La tarifa indicada no existe.');
        }

        $payload = $this->normalizePayload($data);

        $tarifarioTarget = array_key_exists('tarifario_id', $payload) ? (int) $payload['tarifario_id'] : (int) $current['tarifario_id'];
        $nivelTarget = array_key_exists('nivel_id', $payload) ? (int) $payload['nivel_id'] : (int) $current['nivel_id'];
        $gradoTarget = array_key_exists('grado_id', $payload) ? (int) $payload['grado_id'] : (int) $current['grado_id'];
        $jornadaTarget = array_key_exists('jornada', $payload) ? (string) $payload['jornada'] : (string) $current['jornada'];

        $exists = $this->getByUniqueKey($tarifarioTarget, $nivelTarget, $gradoTarget, $jornadaTarget);
        if ($exists !== null && (int) $exists['id'] !== $id) {
            throw new InvalidArgumentException('Ya existe otra tarifa para la combinacion tarifario-nivel-grado-jornada.');
        }

        return $this->update(['id' => $id], $payload);
    }

    public function activarTarifa(int $id): int
    {
        return $this->setActivo($id, 1);
    }

    public function desactivarTarifa(int $id): int
    {
        return $this->setActivo($id, 0);
    }

    private function setActivo(int $id, int $activo): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('La tarifa indicada no existe.');
        }

        if ((int) ($current['activo'] ?? -1) === $activo) {
            return 0;
        }

        return $this->update(['id' => $id], ['activo' => $activo]);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['tarifario_id', 'nivel_id', 'grado_id', 'jornada'];
        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        foreach (['tarifario_id', 'nivel_id', 'grado_id'] as $field) {
            if (array_key_exists($field, $data)) {
                $this->validatePositiveId((int) $data[$field], $field);
            }
        }

        if (array_key_exists('jornada', $data)) {
            $jornada = strtoupper(trim((string) $data['jornada']));
            if ($jornada === '') {
                throw new InvalidArgumentException('jornada no puede estar vacia.');
            }
            $this->validateJornada($jornada);
        }

        foreach (['tarifa_inscripcion', 'mensualidad'] as $field) {
            if (array_key_exists($field, $data)) {
                $value = round((float) $data[$field], 2);
                if ($value < 0) {
                    throw new InvalidArgumentException($field . ' no puede ser negativa.');
                }
            }
        }

        if (array_key_exists('activo', $data)) {
            $activo = (int) $data['activo'];
            if (!in_array($activo, [0, 1], true)) {
                throw new InvalidArgumentException('activo solo permite 0 o 1.');
            }
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;

        foreach (['tarifario_id', 'nivel_id', 'grado_id'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = (int) $payload[$field];
            }
        }

        if (array_key_exists('jornada', $payload)) {
            $payload['jornada'] = strtoupper(trim((string) $payload['jornada']));
        }

        foreach (['tarifa_inscripcion', 'mensualidad'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = round((float) $payload[$field], 2);
            }
        }

        if (array_key_exists('activo', $payload)) {
            $payload['activo'] = (int) $payload['activo'];
        }

        return $payload;
    }

    private function validateJornada(string $jornada): void
    {
        $permitidos = [self::JORNADA_MATUTINO, self::JORNADA_VESPERTINO];
        if (!in_array($jornada, $permitidos, true)) {
            throw new InvalidArgumentException('jornada no es valida para tarifas_grados.');
        }
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
        if ($limit > 5000) {
            return 5000;
        }

        return $limit;
    }
}
