<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class PagosModel extends BaseModel
{
    protected string $table = 'pagos';

    protected array $primaryKey = ['id'];

    private const ESTADO_APLICADO = 'APLICADO';
    private const ESTADO_ANULADO = 'ANULADO';

    protected array $fillable = [
        'estudiante_id',
        'numero_recibo',
        'fecha_pago',
        'metodo_pago_id',
        'referencia',
        'monto_total',
        'estado',
        'observaciones',
        'usuario_id',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByNumeroRecibo(string $numeroRecibo): ?array
    {
        return $this->find(['numero_recibo' => trim($numeroRecibo)]);
    }

    public function getByEstudianteId(int $estudianteId, int $limit = 500): array
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `pagos`
                WHERE `estudiante_id` = :estudiante_id
                ORDER BY `fecha_pago` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estudiante_id', $estudianteId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByEstado(string $estado, int $limit = 500): array
    {
        $estadoNormalizado = strtoupper(trim($estado));
        $this->validateEstado($estadoNormalizado);
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `pagos`
                WHERE `estado` = :estado
                ORDER BY `fecha_pago` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estado', $estadoNormalizado);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByMetodoPagoId(int $metodoPagoId, int $limit = 500): array
    {
        $this->validatePositiveId($metodoPagoId, 'metodo_pago_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `pagos`
                WHERE `metodo_pago_id` = :metodo_pago_id
                ORDER BY `fecha_pago` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':metodo_pago_id', $metodoPagoId, PDO::PARAM_INT);
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
        $sql = 'SELECT * FROM `pagos`
                WHERE `fecha_pago` >= :fecha_inicio AND `fecha_pago` <= :fecha_fin
                ORDER BY `fecha_pago` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':fecha_inicio', $fechaInicio);
        $stmt->bindValue(':fecha_fin', $fechaFin);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createPago(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        if ($this->getByNumeroRecibo((string) $payload['numero_recibo']) !== null) {
            throw new InvalidArgumentException('Ya existe un pago con ese numero_recibo.');
        }

        if (!array_key_exists('estado', $payload)) {
            $payload['estado'] = self::ESTADO_APLICADO;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el pago.');
        }

        return $newId;
    }

    public function updatePago(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El pago indicado no existe.');
        }

        $payload = $this->normalizePayload($data);

        if (array_key_exists('numero_recibo', $payload)) {
            $existing = $this->getByNumeroRecibo((string) $payload['numero_recibo']);
            if ($existing !== null && (int) $existing['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe un pago con ese numero_recibo.');
            }
        }

        return $this->update(['id' => $id], $payload);
    }

    public function anularPago(int $id, ?string $observaciones = null): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El pago indicado no existe.');
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

    public function aplicarPago(int $id): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El pago indicado no existe.');
        }

        if (($current['estado'] ?? null) === self::ESTADO_APLICADO) {
            return 0;
        }

        return $this->update(['id' => $id], ['estado' => self::ESTADO_APLICADO]);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['estudiante_id', 'numero_recibo', 'metodo_pago_id', 'monto_total'];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        foreach (['estudiante_id', 'metodo_pago_id', 'usuario_id'] as $field) {
            if (array_key_exists($field, $data) && $data[$field] !== null) {
                $this->validatePositiveId((int) $data[$field], $field);
            }
        }

        if (array_key_exists('numero_recibo', $data)) {
            $numeroRecibo = trim((string) $data['numero_recibo']);
            if ($numeroRecibo === '') {
                throw new InvalidArgumentException('numero_recibo no puede estar vacio.');
            }
            if (mb_strlen($numeroRecibo) > 50) {
                throw new InvalidArgumentException('numero_recibo no puede exceder 50 caracteres.');
            }
        }

        if (array_key_exists('fecha_pago', $data) && $data['fecha_pago'] !== null) {
            $fechaPago = trim((string) $data['fecha_pago']);
            if ($fechaPago !== '' && !$this->isValidDateTime($fechaPago)) {
                throw new InvalidArgumentException('fecha_pago debe tener formato Y-m-d H:i:s.');
            }
        }

        if (array_key_exists('referencia', $data) && $data['referencia'] !== null) {
            $referencia = trim((string) $data['referencia']);
            if ($referencia !== '' && mb_strlen($referencia) > 150) {
                throw new InvalidArgumentException('referencia no puede exceder 150 caracteres.');
            }
        }

        if (array_key_exists('monto_total', $data)) {
            $monto = round((float) $data['monto_total'], 2);
            if ($monto <= 0) {
                throw new InvalidArgumentException('monto_total debe ser mayor que cero.');
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

        foreach (['estudiante_id', 'metodo_pago_id', 'usuario_id'] as $field) {
            if (array_key_exists($field, $payload) && $payload[$field] !== null) {
                $payload[$field] = (int) $payload[$field];
            }
        }

        if (array_key_exists('numero_recibo', $payload)) {
            $payload['numero_recibo'] = trim((string) $payload['numero_recibo']);
        }

        if (array_key_exists('fecha_pago', $payload) && $payload['fecha_pago'] !== null) {
            $fechaPago = trim((string) $payload['fecha_pago']);
            $payload['fecha_pago'] = $fechaPago === '' ? null : $fechaPago;
        }

        foreach (['referencia', 'observaciones'] as $field) {
            if (array_key_exists($field, $payload) && $payload[$field] !== null) {
                $value = trim((string) $payload[$field]);
                $payload[$field] = $value === '' ? null : $value;
            }
        }

        if (array_key_exists('monto_total', $payload)) {
            $payload['monto_total'] = round((float) $payload['monto_total'], 2);
        }

        if (array_key_exists('estado', $payload)) {
            $payload['estado'] = strtoupper(trim((string) $payload['estado']));
        }

        return $payload;
    }

    private function validateEstado(string $estado): void
    {
        $permitidos = [self::ESTADO_APLICADO, self::ESTADO_ANULADO];
        if (!in_array($estado, $permitidos, true)) {
            throw new InvalidArgumentException('estado no es valido para pagos.');
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
