<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class PeriodosCobroModel extends BaseModel
{
    protected string $table = 'periodos_cobro';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'anio_escolar_id',
        'nombre',
        'numero_mes',
        'fecha_inicio',
        'fecha_vencimiento',
        'es_junio',
        'activo',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByAnioEscolarId(int $anioEscolarId, int $limit = 24): array
    {
        $this->validatePositiveId($anioEscolarId, 'anio_escolar_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `periodos_cobro`
                WHERE `anio_escolar_id` = :anio_escolar_id
                ORDER BY `numero_mes` ASC, `id` ASC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':anio_escolar_id', $anioEscolarId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByAnioAndMes(int $anioEscolarId, int $numeroMes): ?array
    {
        $this->validatePositiveId($anioEscolarId, 'anio_escolar_id');
        $this->validateNumeroMes($numeroMes);

        return $this->find([
            'anio_escolar_id' => $anioEscolarId,
            'numero_mes' => $numeroMes,
        ]);
    }

    public function getActivosByAnio(int $anioEscolarId, int $limit = 24): array
    {
        $this->validatePositiveId($anioEscolarId, 'anio_escolar_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `periodos_cobro`
                WHERE `anio_escolar_id` = :anio_escolar_id
                  AND `activo` = 1
                ORDER BY `numero_mes` ASC, `id` ASC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':anio_escolar_id', $anioEscolarId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getPeriodoJunioByAnio(int $anioEscolarId): ?array
    {
        $this->validatePositiveId($anioEscolarId, 'anio_escolar_id');

        $sql = 'SELECT * FROM `periodos_cobro`
                WHERE `anio_escolar_id` = :anio_escolar_id
                  AND `es_junio` = 1
                ORDER BY `id` DESC
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':anio_escolar_id', $anioEscolarId, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    public function createPeriodo(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        $exists = $this->getByAnioAndMes((int) $payload['anio_escolar_id'], (int) $payload['numero_mes']);
        if ($exists !== null) {
            throw new InvalidArgumentException('Ya existe un periodo para la combinacion anio_escolar_id y numero_mes.');
        }

        if (!array_key_exists('activo', $payload)) {
            $payload['activo'] = 1;
        }

        if (!array_key_exists('es_junio', $payload)) {
            $payload['es_junio'] = 0;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el periodo de cobro.');
        }

        return $newId;
    }

    public function updatePeriodo(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El periodo de cobro indicado no existe.');
        }

        $payload = $this->normalizePayload($data);

        $anioTarget = array_key_exists('anio_escolar_id', $payload)
            ? (int) $payload['anio_escolar_id']
            : (int) $current['anio_escolar_id'];
        $mesTarget = array_key_exists('numero_mes', $payload)
            ? (int) $payload['numero_mes']
            : (int) $current['numero_mes'];

        $exists = $this->getByAnioAndMes($anioTarget, $mesTarget);
        if ($exists !== null && (int) $exists['id'] !== $id) {
            throw new InvalidArgumentException('Ya existe otro periodo para la combinacion anio_escolar_id y numero_mes.');
        }

        return $this->update(['id' => $id], $payload);
    }

    public function activarPeriodo(int $id): int
    {
        return $this->setActivo($id, 1);
    }

    public function desactivarPeriodo(int $id): int
    {
        return $this->setActivo($id, 0);
    }

    private function setActivo(int $id, int $activo): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El periodo de cobro indicado no existe.');
        }

        if ((int) ($current['activo'] ?? -1) === $activo) {
            return 0;
        }

        return $this->update(['id' => $id], ['activo' => $activo]);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['anio_escolar_id', 'nombre', 'numero_mes', 'fecha_inicio', 'fecha_vencimiento'];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        if (array_key_exists('anio_escolar_id', $data)) {
            $this->validatePositiveId((int) $data['anio_escolar_id'], 'anio_escolar_id');
        }

        if (array_key_exists('nombre', $data)) {
            $nombre = trim((string) $data['nombre']);
            if ($nombre === '') {
                throw new InvalidArgumentException('nombre no puede estar vacio.');
            }
            if (mb_strlen($nombre) > 50) {
                throw new InvalidArgumentException('nombre no puede exceder 50 caracteres.');
            }
        }

        if (array_key_exists('numero_mes', $data)) {
            $this->validateNumeroMes((int) $data['numero_mes']);
        }

        if (array_key_exists('fecha_inicio', $data)) {
            $fechaInicio = trim((string) $data['fecha_inicio']);
            if ($fechaInicio === '' || !$this->isValidDate($fechaInicio)) {
                throw new InvalidArgumentException('fecha_inicio debe tener formato Y-m-d.');
            }
        }

        if (array_key_exists('fecha_vencimiento', $data)) {
            $fechaVenc = trim((string) $data['fecha_vencimiento']);
            if ($fechaVenc === '' || !$this->isValidDate($fechaVenc)) {
                throw new InvalidArgumentException('fecha_vencimiento debe tener formato Y-m-d.');
            }
        }

        if (array_key_exists('fecha_inicio', $data) && array_key_exists('fecha_vencimiento', $data)) {
            $fechaInicio = trim((string) $data['fecha_inicio']);
            $fechaVenc = trim((string) $data['fecha_vencimiento']);
            if ($this->isValidDate($fechaInicio) && $this->isValidDate($fechaVenc) && $fechaInicio > $fechaVenc) {
                throw new InvalidArgumentException('fecha_inicio no puede ser mayor que fecha_vencimiento.');
            }
        }

        foreach (['es_junio', 'activo'] as $field) {
            if (array_key_exists($field, $data)) {
                $value = (int) $data[$field];
                if (!in_array($value, [0, 1], true)) {
                    throw new InvalidArgumentException($field . ' solo permite 0 o 1.');
                }
            }
        }

        if (array_key_exists('numero_mes', $data) && array_key_exists('es_junio', $data)) {
            $mes = (int) $data['numero_mes'];
            $esJunio = (int) $data['es_junio'];
            if ($esJunio === 1 && $mes !== 6) {
                throw new InvalidArgumentException('es_junio solo puede ser 1 cuando numero_mes es 6.');
            }
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;

        if (array_key_exists('anio_escolar_id', $payload)) {
            $payload['anio_escolar_id'] = (int) $payload['anio_escolar_id'];
        }

        if (array_key_exists('nombre', $payload)) {
            $payload['nombre'] = trim((string) $payload['nombre']);
        }

        if (array_key_exists('numero_mes', $payload)) {
            $payload['numero_mes'] = (int) $payload['numero_mes'];
        }

        foreach (['fecha_inicio', 'fecha_vencimiento'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = trim((string) $payload[$field]);
            }
        }

        foreach (['es_junio', 'activo'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = (int) $payload[$field];
            }
        }

        return $payload;
    }

    private function validatePositiveId(int $id, string $field): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException($field . ' debe ser mayor que cero.');
        }
    }

    private function validateNumeroMes(int $numeroMes): void
    {
        if ($numeroMes < 1 || $numeroMes > 12) {
            throw new InvalidArgumentException('numero_mes debe estar entre 1 y 12.');
        }
    }

    private function isValidDate(string $date): bool
    {
        $dt = DateTime::createFromFormat('Y-m-d', $date);
        return $dt !== false && $dt->format('Y-m-d') === $date;
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
