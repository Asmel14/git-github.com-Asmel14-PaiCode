<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class MovimientosBancariosModel extends BaseModel
{
    protected string $table = 'movimientos_bancarios';

    protected array $primaryKey = ['id'];

    private const TIPO_DEPOSITO = 'DEPOSITO';
    private const TIPO_TRANSFERENCIA_ENTRADA = 'TRANSFERENCIA_ENTRADA';
    private const TIPO_TRANSFERENCIA_SALIDA = 'TRANSFERENCIA_SALIDA';
    private const TIPO_RETIRO = 'RETIRO';
    private const TIPO_CHEQUE = 'CHEQUE';
    private const TIPO_COMISION = 'COMISION';
    private const TIPO_OTRO = 'OTRO';

    private const ESTADO_APLICADO = 'APLICADO';
    private const ESTADO_ANULADO = 'ANULADO';

    protected array $fillable = [
        'cuenta_bancaria_id',
        'tipo',
        'concepto',
        'monto',
        'fecha_movimiento',
        'referencia',
        'pago_id',
        'usuario_id',
        'estado',
        'observaciones',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByCuentaId(int $cuentaId, int $limit = 500): array
    {
        $this->validatePositiveId($cuentaId, 'cuenta_bancaria_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `movimientos_bancarios`
                WHERE `cuenta_bancaria_id` = :cuenta_id
                ORDER BY `fecha_movimiento` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':cuenta_id', $cuentaId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByEstado(string $estado, int $limit = 500): array
    {
        $estadoNormalizado = strtoupper(trim($estado));
        $this->validateEstado($estadoNormalizado);
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `movimientos_bancarios`
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

        $sql = 'SELECT * FROM `movimientos_bancarios`
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
        $sql = 'SELECT * FROM `movimientos_bancarios`
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

    public function createMovimiento(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        if (!array_key_exists('estado', $payload)) {
            $payload['estado'] = self::ESTADO_APLICADO;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el movimiento bancario.');
        }

        return $newId;
    }

    public function updateMovimiento(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El movimiento bancario indicado no existe.');
        }

        $payload = $this->normalizePayload($data);
        return $this->update(['id' => $id], $payload);
    }

    public function anularMovimiento(int $id, ?string $observaciones = null): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El movimiento bancario indicado no existe.');
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
            throw new InvalidArgumentException('El movimiento bancario indicado no existe.');
        }

        if (($current['estado'] ?? null) === self::ESTADO_APLICADO) {
            return 0;
        }

        return $this->update(['id' => $id], ['estado' => self::ESTADO_APLICADO]);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['cuenta_bancaria_id', 'tipo', 'concepto', 'monto'];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        if (array_key_exists('cuenta_bancaria_id', $data)) {
            $this->validatePositiveId((int) $data['cuenta_bancaria_id'], 'cuenta_bancaria_id');
        }

        if (array_key_exists('tipo', $data)) {
            $tipo = strtoupper(trim((string) $data['tipo']));
            if ($tipo === '') {
                throw new InvalidArgumentException('tipo no puede estar vacio.');
            }
            $this->validateTipo($tipo);
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

        if (array_key_exists('referencia', $data) && $data['referencia'] !== null) {
            $referencia = trim((string) $data['referencia']);
            if ($referencia !== '' && mb_strlen($referencia) > 150) {
                throw new InvalidArgumentException('referencia no puede exceder 150 caracteres.');
            }
        }

        foreach (['pago_id', 'usuario_id'] as $field) {
            if (array_key_exists($field, $data) && $data[$field] !== null) {
                $this->validatePositiveId((int) $data[$field], $field);
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

        foreach (['cuenta_bancaria_id', 'pago_id', 'usuario_id'] as $field) {
            if (array_key_exists($field, $payload) && $payload[$field] !== null) {
                $payload[$field] = (int) $payload[$field];
            }
        }

        if (array_key_exists('tipo', $payload)) {
            $payload['tipo'] = strtoupper(trim((string) $payload['tipo']));
        }

        if (array_key_exists('concepto', $payload)) {
            $payload['concepto'] = trim((string) $payload['concepto']);
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
        $permitidos = [
            self::TIPO_DEPOSITO,
            self::TIPO_TRANSFERENCIA_ENTRADA,
            self::TIPO_TRANSFERENCIA_SALIDA,
            self::TIPO_RETIRO,
            self::TIPO_CHEQUE,
            self::TIPO_COMISION,
            self::TIPO_OTRO,
        ];

        if (!in_array($tipo, $permitidos, true)) {
            throw new InvalidArgumentException('tipo no es valido para movimientos_bancarios.');
        }
    }

    private function validateEstado(string $estado): void
    {
        $permitidos = [self::ESTADO_APLICADO, self::ESTADO_ANULADO];
        if (!in_array($estado, $permitidos, true)) {
            throw new InvalidArgumentException('estado no es valido para movimientos_bancarios.');
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
