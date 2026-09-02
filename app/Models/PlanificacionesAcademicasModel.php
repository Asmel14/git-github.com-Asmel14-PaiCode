<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class PlanificacionesAcademicasModel extends BaseModel
{
    protected string $table = 'planificaciones_academicas';

    protected array $primaryKey = ['id'];

    private const JORNADA_MATUTINO = 'MATUTINO';
    private const JORNADA_VESPERTINO = 'VESPERTINO';

    protected array $fillable = [
        'anio_escolar_id',
        'nivel_id',
        'grado_id',
        'seccion_id',
        'tanda_id',
        'jornada',
        'estado',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByAnioEscolarId(int $anioEscolarId, int $limit = 500): array
    {
        $this->validatePositiveId($anioEscolarId, 'anio_escolar_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `planificaciones_academicas`
                WHERE `anio_escolar_id` = :anio_escolar_id
                ORDER BY `nivel_id` ASC, `grado_id` ASC, `seccion_id` ASC, `jornada` ASC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':anio_escolar_id', $anioEscolarId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByGradoId(int $gradoId, int $limit = 500): array
    {
        $this->validatePositiveId($gradoId, 'grado_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `planificaciones_academicas`
                WHERE `grado_id` = :grado_id
                ORDER BY `anio_escolar_id` DESC, `seccion_id` ASC, `jornada` ASC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':grado_id', $gradoId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getBySeccionId(int $seccionId, int $limit = 500): array
    {
        $this->validatePositiveId($seccionId, 'seccion_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `planificaciones_academicas`
                WHERE `seccion_id` = :seccion_id
                ORDER BY `anio_escolar_id` DESC, `grado_id` ASC, `jornada` ASC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':seccion_id', $seccionId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByJornada(string $jornada, int $limit = 500): array
    {
        $jornadaNorm = strtoupper(trim($jornada));
        $this->validateJornada($jornadaNorm);
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `planificaciones_academicas`
                WHERE `jornada` = :jornada
                ORDER BY `anio_escolar_id` DESC, `nivel_id` ASC, `grado_id` ASC, `seccion_id` ASC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':jornada', $jornadaNorm);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByUniqueKey(
        int $anioEscolarId,
        int $nivelId,
        int $gradoId,
        int $seccionId,
        string $jornada
    ): ?array {
        $this->validatePositiveId($anioEscolarId, 'anio_escolar_id');
        $this->validatePositiveId($nivelId, 'nivel_id');
        $this->validatePositiveId($gradoId, 'grado_id');
        $this->validatePositiveId($seccionId, 'seccion_id');

        $jornadaNorm = strtoupper(trim($jornada));
        $this->validateJornada($jornadaNorm);

        return $this->find([
            'anio_escolar_id' => $anioEscolarId,
            'nivel_id' => $nivelId,
            'grado_id' => $gradoId,
            'seccion_id' => $seccionId,
            'jornada' => $jornadaNorm,
        ]);
    }

    public function createPlanificacion(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        $exists = $this->getByUniqueKey(
            (int) $payload['anio_escolar_id'],
            (int) $payload['nivel_id'],
            (int) $payload['grado_id'],
            (int) $payload['seccion_id'],
            (string) $payload['jornada']
        );
        if ($exists !== null) {
            throw new InvalidArgumentException('Ya existe una planificacion con esa combinacion de anio, nivel, grado, seccion y jornada.');
        }

        if (!array_key_exists('estado', $payload)) {
            $payload['estado'] = 1;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear la planificacion academica.');
        }

        return $newId;
    }

    public function updatePlanificacion(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('La planificacion academica indicada no existe.');
        }

        $payload = $this->normalizePayload($data);

        $anioTarget = array_key_exists('anio_escolar_id', $payload) ? (int) $payload['anio_escolar_id'] : (int) $current['anio_escolar_id'];
        $nivelTarget = array_key_exists('nivel_id', $payload) ? (int) $payload['nivel_id'] : (int) $current['nivel_id'];
        $gradoTarget = array_key_exists('grado_id', $payload) ? (int) $payload['grado_id'] : (int) $current['grado_id'];
        $seccionTarget = array_key_exists('seccion_id', $payload) ? (int) $payload['seccion_id'] : (int) $current['seccion_id'];
        $jornadaTarget = array_key_exists('jornada', $payload) ? (string) $payload['jornada'] : (string) $current['jornada'];

        $exists = $this->getByUniqueKey($anioTarget, $nivelTarget, $gradoTarget, $seccionTarget, $jornadaTarget);
        if ($exists !== null && (int) $exists['id'] !== $id) {
            throw new InvalidArgumentException('Ya existe otra planificacion con esa combinacion de anio, nivel, grado, seccion y jornada.');
        }

        return $this->update(['id' => $id], $payload);
    }

    public function activarPlanificacion(int $id): int
    {
        return $this->setEstado($id, 1);
    }

    public function desactivarPlanificacion(int $id): int
    {
        return $this->setEstado($id, 0);
    }

    private function setEstado(int $id, int $estado): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('La planificacion academica indicada no existe.');
        }

        if ((int) ($current['estado'] ?? -1) === $estado) {
            return 0;
        }

        return $this->update(['id' => $id], ['estado' => $estado]);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['anio_escolar_id', 'nivel_id', 'grado_id', 'seccion_id'];
        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }

            if (!array_key_exists('jornada', $data) && !array_key_exists('tanda_id', $data)) {
                throw new InvalidArgumentException('Debe enviar jornada o tanda_id.');
            }
        }

        foreach (['anio_escolar_id', 'nivel_id', 'grado_id', 'seccion_id', 'tanda_id'] as $field) {
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

        foreach (['anio_escolar_id', 'nivel_id', 'grado_id', 'seccion_id'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = (int) $payload[$field];
            }
        }

        if (array_key_exists('jornada', $payload)) {
            $payload['jornada'] = strtoupper(trim((string) $payload['jornada']));
        }

        if (array_key_exists('estado', $payload)) {
            $payload['estado'] = (int) $payload['estado'];
        }

        return $payload;
    }

    private function validateJornada(string $jornada): void
    {
        $permitidos = [self::JORNADA_MATUTINO, self::JORNADA_VESPERTINO];
        if (!in_array($jornada, $permitidos, true)) {
            throw new InvalidArgumentException('jornada no es valida para planificaciones_academicas.');
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
        if ($limit > 3000) {
            return 3000;
        }

        return $limit;
    }
}
