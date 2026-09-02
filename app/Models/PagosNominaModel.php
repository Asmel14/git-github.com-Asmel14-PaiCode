<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class PagosNominaModel extends BaseModel
{
    protected string $table = 'pagos_nomina';

    protected array $primaryKey = ['id'];

    private const METODO_EFECTIVO = 'EFECTIVO';
    private const METODO_TRANSFERENCIA = 'TRANSFERENCIA';
    private const METODO_CHEQUE = 'CHEQUE';

    private const ESTADO_PENDIENTE = 'PENDIENTE';
    private const ESTADO_CONFIRMADO = 'CONFIRMADO';
    private const ESTADO_ANULADO = 'ANULADO';

    protected array $fillable = [
        'nomina_detalle_id',
        'fecha_pago',
        'metodo_pago',
        'banco',
        'numero_referencia',
        'monto',
        'estado',
        'observaciones',
        'usuario_id',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByNominaDetalleId(int $nominaDetalleId, int $limit = 1000): array
    {
        $this->validatePositiveId($nominaDetalleId, 'nomina_detalle_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `pagos_nomina`
                WHERE `nomina_detalle_id` = :nomina_detalle_id
                ORDER BY `fecha_pago` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nomina_detalle_id', $nominaDetalleId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByEstado(string $estado, int $limit = 1000): array
    {
        $estadoNorm = strtoupper(trim($estado));
        $this->validateEstado($estadoNorm);
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `pagos_nomina`
                WHERE `estado` = :estado
                ORDER BY `fecha_pago` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estado', $estadoNorm);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByMetodoPago(string $metodoPago, int $limit = 1000): array
    {
        $metodoNorm = strtoupper(trim($metodoPago));
        $this->validateMetodoPago($metodoNorm);
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `pagos_nomina`
                WHERE `metodo_pago` = :metodo_pago
                ORDER BY `fecha_pago` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':metodo_pago', $metodoNorm);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByRangoFechas(string $fechaInicio, string $fechaFin, int $limit = 1000): array
    {
        if (!$this->isValidDate($fechaInicio) || !$this->isValidDate($fechaFin)) {
            throw new InvalidArgumentException('fechaInicio y fechaFin deben tener formato Y-m-d.');
        }

        if ($fechaInicio > $fechaFin) {
            throw new InvalidArgumentException('fechaInicio no puede ser mayor que fechaFin.');
        }

        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `pagos_nomina`
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

    public function createPagoNomina(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        if (!array_key_exists('estado', $payload)) {
            $payload['estado'] = self::ESTADO_PENDIENTE;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el pago de nomina.');
        }

        return $newId;
    }

    public function updatePagoNomina(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El pago de nomina indicado no existe.');
        }

        $payload = $this->normalizePayload($data);
        return $this->update(['id' => $id], $payload);
    }

    public function confirmarPago(int $id): int
    {
        return $this->setEstado($id, self::ESTADO_CONFIRMADO);
    }

    public function anularPago(int $id, ?string $observaciones = null): int
    {
        $payload = ['estado' => self::ESTADO_ANULADO];
        if ($observaciones !== null) {
            $obs = trim($observaciones);
            $payload['observaciones'] = $obs === '' ? null : $obs;
        }

        $this->validateData($payload, true);
        return $this->updatePagoNomina($id, $payload);
    }

    private function setEstado(int $id, string $estado): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El pago de nomina indicado no existe.');
        }

        if (($current['estado'] ?? null) === $estado) {
            return 0;
        }

        return $this->update(['id' => $id], ['estado' => $estado]);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['nomina_detalle_id', 'fecha_pago', 'metodo_pago', 'monto'];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        foreach (['nomina_detalle_id', 'usuario_id'] as $field) {
            if (array_key_exists($field, $data) && $data[$field] !== null) {
                $this->validatePositiveId((int) $data[$field], $field);
            }
        }

        if (array_key_exists('fecha_pago', $data)) {
            $fecha = trim((string) $data['fecha_pago']);
            if ($fecha === '' || !$this->isValidDate($fecha)) {
                throw new InvalidArgumentException('fecha_pago debe tener formato Y-m-d.');
            }
        }

        if (array_key_exists('metodo_pago', $data)) {
            $metodo = strtoupper(trim((string) $data['metodo_pago']));
            if ($metodo === '') {
                throw new InvalidArgumentException('metodo_pago no puede estar vacio.');
            }
            $this->validateMetodoPago($metodo);
        }

        if (array_key_exists('monto', $data)) {
            $monto = round((float) $data['monto'], 2);
            if ($monto <= 0) {
                throw new InvalidArgumentException('monto debe ser mayor que cero.');
            }
        }

        if (array_key_exists('estado', $data)) {
            $estado = strtoupper(trim((string) $data['estado']));
            if ($estado === '') {
                throw new InvalidArgumentException('estado no puede estar vacio.');
            }
            $this->validateEstado($estado);
        }

        if (array_key_exists('banco', $data) && $data['banco'] !== null) {
            $banco = trim((string) $data['banco']);
            if ($banco !== '' && mb_strlen($banco) > 150) {
                throw new InvalidArgumentException('banco no puede exceder 150 caracteres.');
            }
        }

        if (array_key_exists('numero_referencia', $data) && $data['numero_referencia'] !== null) {
            $ref = trim((string) $data['numero_referencia']);
            if ($ref !== '' && mb_strlen($ref) > 100) {
                throw new InvalidArgumentException('numero_referencia no puede exceder 100 caracteres.');
            }
        }

        if (array_key_exists('observaciones', $data) && $data['observaciones'] !== null) {
            $obs = trim((string) $data['observaciones']);
            if ($obs !== '' && mb_strlen($obs) > 255) {
                throw new InvalidArgumentException('observaciones no puede exceder 255 caracteres.');
            }
        }

        $metodoParaRegla = array_key_exists('metodo_pago', $data)
            ? strtoupper(trim((string) $data['metodo_pago']))
            : null;

        if ($metodoParaRegla !== null && in_array($metodoParaRegla, [self::METODO_TRANSFERENCIA, self::METODO_CHEQUE], true)) {
            if (!array_key_exists('numero_referencia', $data) || trim((string) $data['numero_referencia']) === '') {
                throw new InvalidArgumentException('numero_referencia es obligatorio para TRANSFERENCIA y CHEQUE.');
            }
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;

        foreach (['nomina_detalle_id', 'usuario_id'] as $field) {
            if (array_key_exists($field, $payload) && $payload[$field] !== null) {
                $payload[$field] = (int) $payload[$field];
            }
        }

        if (array_key_exists('fecha_pago', $payload)) {
            $payload['fecha_pago'] = trim((string) $payload['fecha_pago']);
        }

        if (array_key_exists('metodo_pago', $payload)) {
            $payload['metodo_pago'] = strtoupper(trim((string) $payload['metodo_pago']));
        }

        foreach (['banco', 'numero_referencia', 'observaciones'] as $field) {
            if (array_key_exists($field, $payload) && $payload[$field] !== null) {
                $value = trim((string) $payload[$field]);
                $payload[$field] = $value === '' ? null : $value;
            }
        }

        if (array_key_exists('monto', $payload)) {
            $payload['monto'] = round((float) $payload['monto'], 2);
        }

        if (array_key_exists('estado', $payload)) {
            $payload['estado'] = strtoupper(trim((string) $payload['estado']));
        }

        return $payload;
    }

    private function validateMetodoPago(string $metodo): void
    {
        $permitidos = [self::METODO_EFECTIVO, self::METODO_TRANSFERENCIA, self::METODO_CHEQUE];
        if (!in_array($metodo, $permitidos, true)) {
            throw new InvalidArgumentException('metodo_pago no es valido para pagos_nomina.');
        }
    }

    private function validateEstado(string $estado): void
    {
        $permitidos = [self::ESTADO_PENDIENTE, self::ESTADO_CONFIRMADO, self::ESTADO_ANULADO];
        if (!in_array($estado, $permitidos, true)) {
            throw new InvalidArgumentException('estado no es valido para pagos_nomina.');
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
