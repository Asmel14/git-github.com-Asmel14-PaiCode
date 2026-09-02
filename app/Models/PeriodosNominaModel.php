<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class PeriodosNominaModel extends BaseModel
{
    protected string $table = 'periodos_nomina';

    protected array $primaryKey = ['id'];

    private const ESTADO_ABIERTO = 'ABIERTO';
    private const ESTADO_PROCESADO = 'PROCESADO';
    private const ESTADO_PAGADO = 'PAGADO';
    private const ESTADO_CERRADO = 'CERRADO';

    protected array $fillable = [
        'anio_escolar_id',
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'fecha_pago',
        'estado',
        'observaciones',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByAnioEscolarId(int $anioEscolarId, int $limit = 100): array
    {
        $this->validatePositiveId($anioEscolarId, 'anio_escolar_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `periodos_nomina`
                WHERE `anio_escolar_id` = :anio_escolar_id
                ORDER BY `fecha_inicio` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':anio_escolar_id', $anioEscolarId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByEstado(string $estado, int $limit = 100): array
    {
        $estadoNorm = strtoupper(trim($estado));
        $this->validateEstado($estadoNorm);
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `periodos_nomina`
                WHERE `estado` = :estado
                ORDER BY `fecha_inicio` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estado', $estadoNorm);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByFechaRange(string $fechaInicio, string $fechaFin): ?array
    {
        if (!$this->isValidDate($fechaInicio) || !$this->isValidDate($fechaFin)) {
            throw new InvalidArgumentException('fechaInicio y fechaFin deben tener formato Y-m-d.');
        }

        return $this->find([
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
        ]);
    }

    public function getByRangoInicio(string $fechaDesde, string $fechaHasta, int $limit = 200): array
    {
        if (!$this->isValidDate($fechaDesde) || !$this->isValidDate($fechaHasta)) {
            throw new InvalidArgumentException('fechaDesde y fechaHasta deben tener formato Y-m-d.');
        }

        if ($fechaDesde > $fechaHasta) {
            throw new InvalidArgumentException('fechaDesde no puede ser mayor que fechaHasta.');
        }

        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `periodos_nomina`
                WHERE `fecha_inicio` >= :fecha_desde AND `fecha_inicio` <= :fecha_hasta
                ORDER BY `fecha_inicio` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':fecha_desde', $fechaDesde);
        $stmt->bindValue(':fecha_hasta', $fechaHasta);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createPeriodo(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        $exists = $this->getByFechaRange((string) $payload['fecha_inicio'], (string) $payload['fecha_fin']);
        if ($exists !== null) {
            throw new InvalidArgumentException('Ya existe un periodo de nomina con ese rango de fechas.');
        }

        if (!array_key_exists('estado', $payload)) {
            $payload['estado'] = self::ESTADO_ABIERTO;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el periodo de nomina.');
        }

        return $newId;
    }

    public function updatePeriodo(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El periodo de nomina indicado no existe.');
        }

        $payload = $this->normalizePayload($data);

        $targetInicio = array_key_exists('fecha_inicio', $payload)
            ? (string) $payload['fecha_inicio']
            : (string) $current['fecha_inicio'];
        $targetFin = array_key_exists('fecha_fin', $payload)
            ? (string) $payload['fecha_fin']
            : (string) $current['fecha_fin'];

        $exists = $this->getByFechaRange($targetInicio, $targetFin);
        if ($exists !== null && (int) $exists['id'] !== $id) {
            throw new InvalidArgumentException('Ya existe otro periodo de nomina con ese rango de fechas.');
        }

        return $this->update(['id' => $id], $payload);
    }

    public function marcarProcesado(int $id): int
    {
        return $this->setEstado($id, self::ESTADO_PROCESADO);
    }

    public function marcarPagado(int $id): int
    {
        return $this->setEstado($id, self::ESTADO_PAGADO);
    }

    public function cerrarPeriodo(int $id): int
    {
        return $this->setEstado($id, self::ESTADO_CERRADO);
    }

    public function reabrirPeriodo(int $id): int
    {
        return $this->setEstado($id, self::ESTADO_ABIERTO);
    }

    private function setEstado(int $id, string $estado): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El periodo de nomina indicado no existe.');
        }

        if (($current['estado'] ?? null) === $estado) {
            return 0;
        }

        return $this->update(['id' => $id], ['estado' => $estado]);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['nombre', 'fecha_inicio', 'fecha_fin', 'fecha_pago'];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        if (array_key_exists('anio_escolar_id', $data) && $data['anio_escolar_id'] !== null) {
            $this->validatePositiveId((int) $data['anio_escolar_id'], 'anio_escolar_id');
        }

        if (array_key_exists('nombre', $data)) {
            $nombre = trim((string) $data['nombre']);
            if ($nombre === '') {
                throw new InvalidArgumentException('nombre no puede estar vacio.');
            }
            if (mb_strlen($nombre) > 100) {
                throw new InvalidArgumentException('nombre no puede exceder 100 caracteres.');
            }
        }

        foreach (['fecha_inicio', 'fecha_fin', 'fecha_pago'] as $field) {
            if (array_key_exists($field, $data)) {
                $value = trim((string) $data[$field]);
                if ($value === '' || !$this->isValidDate($value)) {
                    throw new InvalidArgumentException($field . ' debe tener formato Y-m-d.');
                }
            }
        }

        if (array_key_exists('fecha_inicio', $data) && array_key_exists('fecha_fin', $data)) {
            $inicio = trim((string) $data['fecha_inicio']);
            $fin = trim((string) $data['fecha_fin']);
            if ($this->isValidDate($inicio) && $this->isValidDate($fin) && $inicio > $fin) {
                throw new InvalidArgumentException('fecha_inicio no puede ser mayor que fecha_fin.');
            }
        }

        if (array_key_exists('estado', $data)) {
            $estado = strtoupper(trim((string) $data['estado']));
            if ($estado === '') {
                throw new InvalidArgumentException('estado no puede estar vacio.');
            }
            $this->validateEstado($estado);
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

        if (array_key_exists('anio_escolar_id', $payload) && $payload['anio_escolar_id'] !== null) {
            $payload['anio_escolar_id'] = (int) $payload['anio_escolar_id'];
        }

        if (array_key_exists('nombre', $payload)) {
            $payload['nombre'] = trim((string) $payload['nombre']);
        }

        foreach (['fecha_inicio', 'fecha_fin', 'fecha_pago'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = trim((string) $payload[$field]);
            }
        }

        if (array_key_exists('estado', $payload)) {
            $payload['estado'] = strtoupper(trim((string) $payload['estado']));
        }

        if (array_key_exists('observaciones', $payload) && $payload['observaciones'] !== null) {
            $obs = trim((string) $payload['observaciones']);
            $payload['observaciones'] = $obs === '' ? null : $obs;
        }

        return $payload;
    }

    private function validateEstado(string $estado): void
    {
        $permitidos = [
            self::ESTADO_ABIERTO,
            self::ESTADO_PROCESADO,
            self::ESTADO_PAGADO,
            self::ESTADO_CERRADO,
        ];

        if (!in_array($estado, $permitidos, true)) {
            throw new InvalidArgumentException('estado no es valido para periodos_nomina.');
        }
    }

    private function validatePositiveId(int $id, string $field): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException($field . ' debe ser mayor que cero.');
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
