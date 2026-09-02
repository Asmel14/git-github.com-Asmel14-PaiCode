<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class RegistrosCivilesModel extends BaseModel
{
    protected string $table = 'registros_civiles';

    protected array $primaryKey = ['id'];

    private const ESTADO_DECLARADO = 'DECLARADO';
    private const ESTADO_NO_DECLARADO = 'NO_DECLARADO';
    private const ESTADO_NO_DISPONIBLE = 'NO_DISPONIBLE';

    protected array $fillable = [
        'estudiante_id',
        'estado_acta',
        'numero_acta',
        'provincia_jce',
        'municipio_jce',
        'oficialia_jce',
        'libro',
        'folio',
        'anio',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByEstudianteId(int $estudianteId): ?array
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');
        return $this->find(['estudiante_id' => $estudianteId]);
    }

    public function getByEstadoActa(string $estadoActa, int $limit = 500): array
    {
        $estado = strtoupper(trim($estadoActa));
        $this->validateEstadoActa($estado);
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `registros_civiles`
                WHERE `estado_acta` = :estado_acta
                ORDER BY `id` DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estado_acta', $estado);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByAnio(int $anio, int $limit = 500): array
    {
        $this->validateYear($anio);
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `registros_civiles`
                WHERE `anio` = :anio
                ORDER BY `id` DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':anio', $anio, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createRegistro(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        $exists = $this->getByEstudianteId((int) $payload['estudiante_id']);
        if ($exists !== null) {
            throw new InvalidArgumentException('Ya existe un registro civil para ese estudiante.');
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el registro civil.');
        }

        return $newId;
    }

    public function updateRegistro(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El registro civil indicado no existe.');
        }

        $payload = $this->normalizePayload($data);

        if (array_key_exists('estudiante_id', $payload)) {
            $exists = $this->getByEstudianteId((int) $payload['estudiante_id']);
            if ($exists !== null && (int) $exists['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe otro registro civil para ese estudiante.');
            }
        }

        return $this->update(['id' => $id], $payload);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        if (!$isPartial) {
            if (!array_key_exists('estudiante_id', $data)) {
                throw new InvalidArgumentException('El campo estudiante_id es obligatorio.');
            }
        }

        if (array_key_exists('estudiante_id', $data)) {
            $this->validatePositiveId((int) $data['estudiante_id'], 'estudiante_id');
        }

        if (array_key_exists('estado_acta', $data) && $data['estado_acta'] !== null) {
            $estado = strtoupper(trim((string) $data['estado_acta']));
            if ($estado === '') {
                throw new InvalidArgumentException('estado_acta no puede estar vacio.');
            }
            $this->validateEstadoActa($estado);
        }

        $stringRules = [
            'numero_acta' => 50,
            'provincia_jce' => 100,
            'municipio_jce' => 100,
            'oficialia_jce' => 150,
            'libro' => 50,
            'folio' => 50,
        ];

        foreach ($stringRules as $field => $maxLength) {
            if (array_key_exists($field, $data) && $data[$field] !== null) {
                $value = trim((string) $data[$field]);
                if ($value !== '' && mb_strlen($value) > $maxLength) {
                    throw new InvalidArgumentException($field . ' no puede exceder ' . $maxLength . ' caracteres.');
                }
            }
        }

        if (array_key_exists('anio', $data) && $data['anio'] !== null) {
            $this->validateYear((int) $data['anio']);
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;

        if (array_key_exists('estudiante_id', $payload)) {
            $payload['estudiante_id'] = (int) $payload['estudiante_id'];
        }

        if (array_key_exists('estado_acta', $payload) && $payload['estado_acta'] !== null) {
            $estado = strtoupper(trim((string) $payload['estado_acta']));
            $payload['estado_acta'] = $estado === '' ? null : $estado;
        }

        foreach (['numero_acta', 'provincia_jce', 'municipio_jce', 'oficialia_jce', 'libro', 'folio'] as $field) {
            if (array_key_exists($field, $payload) && $payload[$field] !== null) {
                $value = trim((string) $payload[$field]);
                $payload[$field] = $value === '' ? null : $value;
            }
        }

        if (array_key_exists('anio', $payload) && $payload['anio'] !== null) {
            $payload['anio'] = (int) $payload['anio'];
        }

        return $payload;
    }

    private function validatePositiveId(int $id, string $field): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException($field . ' debe ser mayor que cero.');
        }
    }

    private function validateEstadoActa(string $estado): void
    {
        $permitidos = [
            self::ESTADO_DECLARADO,
            self::ESTADO_NO_DECLARADO,
            self::ESTADO_NO_DISPONIBLE,
        ];

        if (!in_array($estado, $permitidos, true)) {
            throw new InvalidArgumentException('estado_acta no es valido para registros_civiles.');
        }
    }

    private function validateYear(int $anio): void
    {
        if ($anio < 1900 || $anio > 2155) {
            throw new InvalidArgumentException('anio debe estar entre 1900 y 2155.');
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
