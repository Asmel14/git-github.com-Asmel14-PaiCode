<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class HistorialAcademicoModel extends BaseModel
{
    protected string $table = 'historial_academico';

    protected array $primaryKey = ['id'];

    private const JORNADA_MATUTINO = 'MATUTINO';
    private const JORNADA_VESPERTINO = 'VESPERTINO';

    private const ESTADO_CURSANDO = 'CURSANDO';
    private const ESTADO_PROMOVIDO = 'PROMOVIDO';
    private const ESTADO_REPROBADO = 'REPROBADO';
    private const ESTADO_RETIRADO = 'RETIRADO';
    private const ESTADO_TRASLADADO = 'TRASLADADO';
    private const ESTADO_GRADUADO = 'GRADUADO';

    protected array $fillable = [
        'estudiante_id',
        'anio_escolar_id',
        'nivel_id',
        'grado_id',
        'seccion_id',
        'jornada',
        'fecha_inicio',
        'fecha_fin',
        'estado',
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

        $sql = 'SELECT * FROM `historial_academico`
                WHERE `estudiante_id` = :estudiante_id
                ORDER BY `anio_escolar_id` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estudiante_id', $estudianteId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByAnioEscolarId(int $anioEscolarId, int $limit = 500): array
    {
        $this->validatePositiveId($anioEscolarId, 'anio_escolar_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `historial_academico`
                WHERE `anio_escolar_id` = :anio_escolar_id
                ORDER BY `nivel_id` ASC, `grado_id` ASC, `seccion_id` ASC, `id` ASC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':anio_escolar_id', $anioEscolarId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getRegistroActualByEstudianteId(int $estudianteId): ?array
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');

        $sql = 'SELECT * FROM `historial_academico`
                WHERE `estudiante_id` = :estudiante_id AND `estado` = :estado
                ORDER BY `id` DESC
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estudiante_id', $estudianteId, PDO::PARAM_INT);
        $stmt->bindValue(':estado', self::ESTADO_CURSANDO);
        $stmt->execute();

        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function createRegistro(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        $existing = $this->find([
            'estudiante_id' => (int) $payload['estudiante_id'],
            'anio_escolar_id' => (int) $payload['anio_escolar_id'],
        ]);

        if ($existing !== null) {
            throw new InvalidArgumentException('Ya existe historial para este estudiante en el anio escolar indicado.');
        }

        if (!array_key_exists('estado', $payload)) {
            $payload['estado'] = self::ESTADO_CURSANDO;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el historial academico.');
        }

        return $newId;
    }

    public function updateRegistro(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El registro de historial academico no existe.');
        }

        $payload = $this->normalizePayload($data);
        return $this->update(['id' => $id], $payload);
    }

    public function cambiarEstado(int $id, string $estado, ?string $observaciones = null): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El registro de historial academico no existe.');
        }

        $estadoNormalizado = strtoupper(trim($estado));
        $this->validateEstado($estadoNormalizado);

        $payload = [
            'estado' => $estadoNormalizado,
        ];

        if ($observaciones !== null) {
            $obs = trim($observaciones);
            $payload['observaciones'] = $obs === '' ? null : $obs;
        }

        if (in_array($estadoNormalizado, [self::ESTADO_PROMOVIDO, self::ESTADO_REPROBADO, self::ESTADO_RETIRADO, self::ESTADO_TRASLADADO, self::ESTADO_GRADUADO], true)) {
            $payload['fecha_fin'] = date('Y-m-d');
        }

        return $this->update(['id' => $id], $payload);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = [
            'estudiante_id',
            'anio_escolar_id',
            'nivel_id',
            'grado_id',
            'seccion_id',
            'jornada',
        ];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        foreach (['estudiante_id', 'anio_escolar_id', 'nivel_id', 'grado_id', 'seccion_id'] as $field) {
            if (array_key_exists($field, $data)) {
                $this->validatePositiveId((int) $data[$field], $field);
            }
        }

        if (array_key_exists('jornada', $data) && $data['jornada'] !== null) {
            $jornada = strtoupper(trim((string) $data['jornada']));
            if ($jornada === '') {
                throw new InvalidArgumentException('jornada no puede estar vacio.');
            }
            $this->validateJornada($jornada);
        }

        if (array_key_exists('estado', $data) && $data['estado'] !== null) {
            $estado = strtoupper(trim((string) $data['estado']));
            if ($estado === '') {
                throw new InvalidArgumentException('estado no puede estar vacio.');
            }
            $this->validateEstado($estado);
        }

        $fechaInicio = null;
        $fechaFin = null;

        if (array_key_exists('fecha_inicio', $data) && $data['fecha_inicio'] !== null && $data['fecha_inicio'] !== '') {
            $fechaInicio = trim((string) $data['fecha_inicio']);
            if (!$this->isValidDate($fechaInicio)) {
                throw new InvalidArgumentException('fecha_inicio debe tener formato Y-m-d.');
            }
        }

        if (array_key_exists('fecha_fin', $data) && $data['fecha_fin'] !== null && $data['fecha_fin'] !== '') {
            $fechaFin = trim((string) $data['fecha_fin']);
            if (!$this->isValidDate($fechaFin)) {
                throw new InvalidArgumentException('fecha_fin debe tener formato Y-m-d.');
            }
        }

        if ($fechaInicio !== null && $fechaFin !== null && $fechaInicio > $fechaFin) {
            throw new InvalidArgumentException('fecha_inicio no puede ser mayor que fecha_fin.');
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

        foreach (['estudiante_id', 'anio_escolar_id', 'nivel_id', 'grado_id', 'seccion_id'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = (int) $payload[$field];
            }
        }

        if (array_key_exists('jornada', $payload) && $payload['jornada'] !== null) {
            $payload['jornada'] = strtoupper(trim((string) $payload['jornada']));
        }

        if (array_key_exists('estado', $payload) && $payload['estado'] !== null) {
            $payload['estado'] = strtoupper(trim((string) $payload['estado']));
        }

        foreach (['fecha_inicio', 'fecha_fin'] as $field) {
            if (!array_key_exists($field, $payload) || $payload[$field] === null) {
                continue;
            }

            $value = trim((string) $payload[$field]);
            $payload[$field] = $value === '' ? null : $value;
        }

        if (array_key_exists('observaciones', $payload) && $payload['observaciones'] !== null) {
            $obs = trim((string) $payload['observaciones']);
            $payload['observaciones'] = $obs === '' ? null : $obs;
        }

        return $payload;
    }

    private function validateJornada(string $jornada): void
    {
        $permitidas = [self::JORNADA_MATUTINO, self::JORNADA_VESPERTINO];
        if (!in_array($jornada, $permitidas, true)) {
            throw new InvalidArgumentException('jornada no es valida para historial_academico.');
        }
    }

    private function validateEstado(string $estado): void
    {
        $permitidos = [
            self::ESTADO_CURSANDO,
            self::ESTADO_PROMOVIDO,
            self::ESTADO_REPROBADO,
            self::ESTADO_RETIRADO,
            self::ESTADO_TRASLADADO,
            self::ESTADO_GRADUADO,
        ];

        if (!in_array($estado, $permitidos, true)) {
            throw new InvalidArgumentException('estado no es valido para historial_academico.');
        }
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
