<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class MovimientosCajaModel extends BaseModel
{
    protected string $table = 'movimientos_caja';

    protected array $primaryKey = ['id'];

    private const TIPO_INGRESO = 'INGRESO';
    private const TIPO_EGRESO = 'EGRESO';

    private const ESTADO_APLICADO = 'APLICADO';
    private const ESTADO_ANULADO = 'ANULADO';

    protected array $fillable = [
        'caja_sesion_id',
        'tipo',
        'categoria',
        'concepto',
        'monto',
        'fecha_movimiento',
        'pago_id',
        'usuario_id',
        'referencia',
        'estado',
        'observaciones',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getBySesionId(int $sesionId, int $limit = 500): array
    {
        $this->validatePositiveId($sesionId, 'caja_sesion_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `movimientos_caja`
                WHERE `caja_sesion_id` = :sesion_id
                ORDER BY `fecha_movimiento` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':sesion_id', $sesionId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByEstado(string $estado, int $limit = 500): array
    {
        $estadoNormalizado = strtoupper(trim($estado));
        $this->validateEstado($estadoNormalizado);
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `movimientos_caja`
                WHERE `estado` = :estado
                ORDER BY `fecha_movimiento` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estado', $estadoNormalizado);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByTipo(string $tipo, int $limit = 500): array
    {
        $tipoNormalizado = strtoupper(trim($tipo));
        $this->validateTipo($tipoNormalizado);
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `movimientos_caja`
                WHERE `tipo` = :tipo
                ORDER BY `fecha_movimiento` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':tipo', $tipoNormalizado);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByRangoFechas(string $fechaInicio, string $fechaFin, int $limit = 1000): array
    {
        if (!$this->isValidDateTime($fechaInicio) || !$this->isValidDateTime($fechaFin)) {
            throw new InvalidArgumentException('fechaInicio y fechaFin deben tener formato Y-m-d H:i:s.');
        }

        if ($fechaInicio > $fechaFin) {
            throw new InvalidArgumentException('fechaInicio no puede ser mayor que fechaFin.');
        }

        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `movimientos_caja`
                WHERE `fecha_movimiento` >= :fecha_inicio AND `fecha_movimiento` <= :fecha_fin
                ORDER BY `fecha_movimiento` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':fecha_inicio', $fechaInicio);
        $stmt->bindValue(':fecha_fin', $fechaFin);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getTotalesAplicadosBySesion(int $sesionId): array
    {
        $this->validatePositiveId($sesionId, 'caja_sesion_id');

        $sql = 'SELECT
                    COALESCE(SUM(CASE WHEN `tipo` = :tipo_ingreso THEN `monto` ELSE 0 END), 0) AS total_ingresos,
                    COALESCE(SUM(CASE WHEN `tipo` = :tipo_egreso THEN `monto` ELSE 0 END), 0) AS total_egresos
                FROM `movimientos_caja`
                WHERE `caja_sesion_id` = :sesion_id
                  AND `estado` = :estado_aplicado';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':tipo_ingreso', self::TIPO_INGRESO);
        $stmt->bindValue(':tipo_egreso', self::TIPO_EGRESO);
        $stmt->bindValue(':sesion_id', $sesionId, PDO::PARAM_INT);
        $stmt->bindValue(':estado_aplicado', self::ESTADO_APLICADO);
        $stmt->execute();

        $row = $stmt->fetch();
        $ingresos = isset($row['total_ingresos']) ? (float) $row['total_ingresos'] : 0.0;
        $egresos = isset($row['total_egresos']) ? (float) $row['total_egresos'] : 0.0;

        return [
            'total_ingresos' => round($ingresos, 2),
            'total_egresos' => round($egresos, 2),
            'balance' => round($ingresos - $egresos, 2),
        ];
    }

    public function createMovimiento(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        if (!array_key_exists('estado', $payload)) {
            $payload['estado'] = self::ESTADO_APLICADO;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el movimiento de caja.');
        }

        return $newId;
    }

    public function updateMovimiento(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El movimiento de caja indicado no existe.');
        }

        $payload = $this->normalizePayload($data);
        return $this->update(['id' => $id], $payload);
    }

    public function anularMovimiento(int $id, ?string $observaciones = null): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El movimiento de caja indicado no existe.');
        }

        if (($current['estado'] ?? null) === self::ESTADO_ANULADO) {
            return 0;
        }

        $payload = ['estado' => self::ESTADO_ANULADO];
        if ($observaciones !== null) {
            $obs = trim($observaciones);
            $payload['observaciones'] = $obs === '' ? null : $obs;
        }

        return $this->update(['id' => $id], $payload);
    }

    public function aplicarMovimiento(int $id): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El movimiento de caja indicado no existe.');
        }

        if (($current['estado'] ?? null) === self::ESTADO_APLICADO) {
            return 0;
        }

        return $this->update(['id' => $id], ['estado' => self::ESTADO_APLICADO]);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['caja_sesion_id', 'tipo', 'categoria', 'concepto', 'monto'];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        if (array_key_exists('caja_sesion_id', $data)) {
            $this->validatePositiveId((int) $data['caja_sesion_id'], 'caja_sesion_id');
        }

        if (array_key_exists('tipo', $data)) {
            $tipo = strtoupper(trim((string) $data['tipo']));
            if ($tipo === '') {
                throw new InvalidArgumentException('tipo no puede estar vacio.');
            }
            $this->validateTipo($tipo);
        }

        if (array_key_exists('categoria', $data)) {
            $categoria = trim((string) $data['categoria']);
            if ($categoria === '') {
                throw new InvalidArgumentException('categoria no puede estar vacia.');
            }
            if (mb_strlen($categoria) > 100) {
                throw new InvalidArgumentException('categoria no puede exceder 100 caracteres.');
            }
        }

        if (array_key_exists('concepto', $data)) {
            $concepto = trim((string) $data['concepto']);
            if ($concepto === '') {
                throw new InvalidArgumentException('concepto no puede estar vacio.');
            }
            if (mb_strlen($concepto) > 255) {
                throw new InvalidArgumentException('concepto no puede exceder 255 caracteres.');
            }
        }

        if (array_key_exists('monto', $data)) {
            $monto = round((float) $data['monto'], 2);
            if ($monto <= 0) {
                throw new InvalidArgumentException('monto debe ser mayor que cero.');
            }
        }

        if (array_key_exists('fecha_movimiento', $data) && $data['fecha_movimiento'] !== null) {
            $fecha = trim((string) $data['fecha_movimiento']);
            if ($fecha !== '' && !$this->isValidDateTime($fecha)) {
                throw new InvalidArgumentException('fecha_movimiento debe tener formato Y-m-d H:i:s.');
            }
        }

        foreach (['pago_id', 'usuario_id'] as $field) {
            if (array_key_exists($field, $data) && $data[$field] !== null) {
                $this->validatePositiveId((int) $data[$field], $field);
            }
        }

        if (array_key_exists('referencia', $data) && $data['referencia'] !== null) {
            $referencia = trim((string) $data['referencia']);
            if ($referencia !== '' && mb_strlen($referencia) > 150) {
                throw new InvalidArgumentException('referencia no puede exceder 150 caracteres.');
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

        foreach (['caja_sesion_id', 'pago_id', 'usuario_id'] as $field) {
            if (array_key_exists($field, $payload) && $payload[$field] !== null) {
                $payload[$field] = (int) $payload[$field];
            }
        }

        if (array_key_exists('tipo', $payload)) {
            $payload['tipo'] = strtoupper(trim((string) $payload['tipo']));
        }

        foreach (['categoria', 'concepto'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = trim((string) $payload[$field]);
            }
        }

        if (array_key_exists('monto', $payload)) {
            $payload['monto'] = round((float) $payload['monto'], 2);
        }

        if (array_key_exists('fecha_movimiento', $payload) && $payload['fecha_movimiento'] !== null) {
            $fecha = trim((string) $payload['fecha_movimiento']);
            $payload['fecha_movimiento'] = $fecha === '' ? null : $fecha;
        }

        foreach (['referencia', 'observaciones'] as $field) {
            if (array_key_exists($field, $payload) && $payload[$field] !== null) {
                $value = trim((string) $payload[$field]);
                $payload[$field] = $value === '' ? null : $value;
            }
        }

        if (array_key_exists('estado', $payload)) {
            $payload['estado'] = strtoupper(trim((string) $payload['estado']));
        }

        return $payload;
    }

    private function validateTipo(string $tipo): void
    {
        $permitidos = [self::TIPO_INGRESO, self::TIPO_EGRESO];
        if (!in_array($tipo, $permitidos, true)) {
            throw new InvalidArgumentException('tipo no es valido para movimientos_caja.');
        }
    }

    private function validateEstado(string $estado): void
    {
        $permitidos = [self::ESTADO_APLICADO, self::ESTADO_ANULADO];
        if (!in_array($estado, $permitidos, true)) {
            throw new InvalidArgumentException('estado no es valido para movimientos_caja.');
        }
    }

    private function validatePositiveId(int $id, string $field): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException($field . ' debe ser mayor que cero.');
        }
    }

    private function isValidDateTime(string $dateTime): bool
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);
        return $dt !== false && $dt->format('Y-m-d H:i:s') === $dateTime;
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
