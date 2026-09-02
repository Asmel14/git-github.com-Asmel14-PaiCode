<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class InscripcionesModel extends BaseModel
{
    protected string $table = 'inscripciones';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'estudiante_id',
        'planificacion_academica_id',
        'centro_procedencia',
        'tarifa_inscripcion',
        'mensualidad',
        'fecha_inscripcion',
        'acepta_terminos',
        'inscripcion_activa',
        'observaciones',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByEstudianteId(int $estudianteId, int $limit = 200): array
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `inscripciones`
                WHERE `estudiante_id` = :estudiante_id
                ORDER BY `fecha_inscripcion` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estudiante_id', $estudianteId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getActivaByEstudianteId(int $estudianteId): ?array
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');

        $sql = 'SELECT * FROM `inscripciones`
                WHERE `estudiante_id` = :estudiante_id
                  AND `inscripcion_activa` = 1
                ORDER BY `fecha_inscripcion` DESC, `id` DESC
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estudiante_id', $estudianteId, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function getByPlanificacionId(int $planificacionId, bool $soloActivas = false, int $limit = 500): array
    {
        $this->validatePositiveId($planificacionId, 'planificacion_academica_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `inscripciones`
                WHERE `planificacion_academica_id` = :planificacion_id';

        if ($soloActivas) {
            $sql .= ' AND `inscripcion_activa` = 1';
        }

        $sql .= ' ORDER BY `fecha_inscripcion` DESC, `id` DESC LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':planificacion_id', $planificacionId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createInscripcion(array $data, bool $desactivarAnterior = false): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        $estudianteId = (int) $payload['estudiante_id'];
        $activaActual = $this->getActivaByEstudianteId($estudianteId);

        if ($activaActual !== null && !$desactivarAnterior) {
            throw new InvalidArgumentException('El estudiante ya tiene una inscripcion activa.');
        }

        if ($activaActual !== null && $desactivarAnterior) {
            $this->desactivarById((int) $activaActual['id']);
        }

        if (!array_key_exists('acepta_terminos', $payload)) {
            $payload['acepta_terminos'] = 0;
        }

        if (!array_key_exists('inscripcion_activa', $payload)) {
            $payload['inscripcion_activa'] = 1;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear la inscripcion.');
        }

        return $newId;
    }

    public function updateInscripcion(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('La inscripcion indicada no existe.');
        }

        $payload = $this->normalizePayload($data);
        return $this->update(['id' => $id], $payload);
    }

    public function activarById(int $id): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('La inscripcion indicada no existe.');
        }

        $estudianteId = (int) $current['estudiante_id'];
        $activa = $this->getActivaByEstudianteId($estudianteId);

        if ($activa !== null && (int) $activa['id'] !== $id) {
            $this->desactivarById((int) $activa['id']);
        }

        return $this->update(['id' => $id], ['inscripcion_activa' => 1]);
    }

    public function desactivarById(int $id): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('La inscripcion indicada no existe.');
        }

        return $this->update(['id' => $id], ['inscripcion_activa' => 0]);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = [
            'estudiante_id',
            'planificacion_academica_id',
            'fecha_inscripcion',
        ];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        foreach (['estudiante_id', 'planificacion_academica_id'] as $field) {
            if (array_key_exists($field, $data)) {
                $this->validatePositiveId((int) $data[$field], $field);
            }
        }

        if (array_key_exists('fecha_inscripcion', $data)) {
            $fecha = trim((string) $data['fecha_inscripcion']);
            if ($fecha === '' || !$this->isValidDate($fecha)) {
                throw new InvalidArgumentException('fecha_inscripcion debe tener formato Y-m-d.');
            }
        }

        foreach (['tarifa_inscripcion', 'mensualidad'] as $field) {
            if (array_key_exists($field, $data)) {
                $valor = round((float) $data[$field], 2);
                if ($valor < 0) {
                    throw new InvalidArgumentException($field . ' no puede ser negativo.');
                }
            }
        }

        if (array_key_exists('centro_procedencia', $data) && $data['centro_procedencia'] !== null) {
            $centro = trim((string) $data['centro_procedencia']);
            if ($centro !== '' && mb_strlen($centro) > 255) {
                throw new InvalidArgumentException('centro_procedencia no puede exceder 255 caracteres.');
            }
        }

        foreach (['acepta_terminos', 'inscripcion_activa'] as $field) {
            if (array_key_exists($field, $data)) {
                $value = (int) $data[$field];
                if (!in_array($value, [0, 1], true)) {
                    throw new InvalidArgumentException($field . ' solo permite 0 o 1.');
                }
            }
        }

        if (array_key_exists('observaciones', $data) && $data['observaciones'] !== null) {
            $obs = trim((string) $data['observaciones']);
            if ($obs !== '' && mb_strlen($obs) > 65535) {
                throw new InvalidArgumentException('observaciones excede el tamano permitido.');
            }
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;

        foreach (['estudiante_id', 'planificacion_academica_id'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = (int) $payload[$field];
            }
        }

        if (array_key_exists('fecha_inscripcion', $payload)) {
            $payload['fecha_inscripcion'] = trim((string) $payload['fecha_inscripcion']);
        }

        foreach (['tarifa_inscripcion', 'mensualidad'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = round((float) $payload[$field], 2);
            }
        }

        if (array_key_exists('centro_procedencia', $payload) && $payload['centro_procedencia'] !== null) {
            $centro = trim((string) $payload['centro_procedencia']);
            $payload['centro_procedencia'] = $centro === '' ? null : $centro;
        }

        foreach (['acepta_terminos', 'inscripcion_activa'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = (int) $payload[$field];
            }
        }

        if (array_key_exists('observaciones', $payload) && $payload['observaciones'] !== null) {
            $obs = trim((string) $payload['observaciones']);
            $payload['observaciones'] = $obs === '' ? null : $obs;
        }

        return $payload;
    }

    private function isValidDate(string $date): bool
    {
        $dt = DateTime::createFromFormat('Y-m-d', $date);
        return $dt !== false && $dt->format('Y-m-d') === $date;
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
