<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class NominaDetallesModel extends BaseModel
{
    protected string $table = 'nomina_detalles';

    protected array $primaryKey = ['id'];

    private const ESTADO_PENDIENTE = 'PENDIENTE';
    private const ESTADO_PAGADO = 'PAGADO';
    private const ESTADO_ANULADO = 'ANULADO';

    protected array $fillable = [
        'nomina_id',
        'personal_id',
        'salario_base',
        'total_ingresos',
        'total_deducciones',
        'salario_neto',
        'dias_trabajados',
        'dias_ausentes',
        'horas_extras',
        'estado_pago',
        'fecha_pago',
        'observaciones',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByNominaId(int $nominaId, int $limit = 1000): array
    {
        $this->validatePositiveId($nominaId, 'nomina_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `nomina_detalles`
                WHERE `nomina_id` = :nomina_id
                ORDER BY `id` ASC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nomina_id', $nominaId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByPersonalId(int $personalId, int $limit = 1000): array
    {
        $this->validatePositiveId($personalId, 'personal_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `nomina_detalles`
                WHERE `personal_id` = :personal_id
                ORDER BY `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':personal_id', $personalId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByNominaAndPersonal(int $nominaId, int $personalId): ?array
    {
        $this->validatePositiveId($nominaId, 'nomina_id');
        $this->validatePositiveId($personalId, 'personal_id');

        return $this->find([
            'nomina_id' => $nominaId,
            'personal_id' => $personalId,
        ]);
    }

    public function getByEstadoPago(string $estadoPago, int $limit = 500): array
    {
        $estado = strtoupper(trim($estadoPago));
        $this->validateEstadoPago($estado);
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `nomina_detalles`
                WHERE `estado_pago` = :estado_pago
                ORDER BY `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estado_pago', $estado);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getTotalesByNominaId(int $nominaId): array
    {
        $this->validatePositiveId($nominaId, 'nomina_id');

        $sql = 'SELECT
                    COALESCE(SUM(`salario_base`), 0) AS total_salario_base,
                    COALESCE(SUM(`total_ingresos`), 0) AS total_ingresos,
                    COALESCE(SUM(`total_deducciones`), 0) AS total_deducciones,
                    COALESCE(SUM(`salario_neto`), 0) AS total_neto
                FROM `nomina_detalles`
                WHERE `nomina_id` = :nomina_id';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nomina_id', $nominaId, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();

        return [
            'total_salario_base' => round((float) ($row['total_salario_base'] ?? 0), 2),
            'total_ingresos' => round((float) ($row['total_ingresos'] ?? 0), 2),
            'total_deducciones' => round((float) ($row['total_deducciones'] ?? 0), 2),
            'total_neto' => round((float) ($row['total_neto'] ?? 0), 2),
        ];
    }

    public function createDetalle(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        $exists = $this->getByNominaAndPersonal((int) $payload['nomina_id'], (int) $payload['personal_id']);
        if ($exists !== null) {
            throw new InvalidArgumentException('Ya existe un detalle para la combinacion nomina-personal indicada.');
        }

        if (!array_key_exists('estado_pago', $payload)) {
            $payload['estado_pago'] = self::ESTADO_PENDIENTE;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el detalle de nomina.');
        }

        return $newId;
    }

    public function updateDetalle(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El detalle de nomina indicado no existe.');
        }

        $payload = $this->normalizePayload($data);

        $targetNominaId = array_key_exists('nomina_id', $payload) ? (int) $payload['nomina_id'] : (int) $current['nomina_id'];
        $targetPersonalId = array_key_exists('personal_id', $payload) ? (int) $payload['personal_id'] : (int) $current['personal_id'];

        $duplicate = $this->getByNominaAndPersonal($targetNominaId, $targetPersonalId);
        if ($duplicate !== null && (int) $duplicate['id'] !== $id) {
            throw new InvalidArgumentException('Ya existe otro detalle con la combinacion nomina-personal indicada.');
        }

        return $this->update(['id' => $id], $payload);
    }

    public function marcarPagado(int $id, ?string $fechaPago = null): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El detalle de nomina indicado no existe.');
        }

        $payload = [
            'estado_pago' => self::ESTADO_PAGADO,
            'fecha_pago' => $fechaPago ?? date('Y-m-d'),
        ];

        $this->validateData($payload, true);
        return $this->update(['id' => $id], $payload);
    }

    public function anularDetalle(int $id, ?string $observaciones = null): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El detalle de nomina indicado no existe.');
        }

        $payload = [
            'estado_pago' => self::ESTADO_ANULADO,
        ];

        if ($observaciones !== null) {
            $obs = trim($observaciones);
            $payload['observaciones'] = $obs === '' ? null : $obs;
        }

        $this->validateData($payload, true);
        return $this->update(['id' => $id], $payload);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = [
            'nomina_id',
            'personal_id',
            'salario_base',
            'total_ingresos',
            'total_deducciones',
            'salario_neto',
            'dias_trabajados',
            'dias_ausentes',
            'horas_extras',
        ];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        foreach (['nomina_id', 'personal_id'] as $field) {
            if (array_key_exists($field, $data)) {
                $this->validatePositiveId((int) $data[$field], $field);
            }
        }

        foreach (['salario_base', 'total_ingresos', 'total_deducciones', 'salario_neto'] as $field) {
            if (array_key_exists($field, $data)) {
                $value = round((float) $data[$field], 2);
                if ($value < 0) {
                    throw new InvalidArgumentException($field . ' no puede ser negativo.');
                }
            }
        }

        if (
            array_key_exists('total_ingresos', $data)
            && array_key_exists('total_deducciones', $data)
            && array_key_exists('salario_neto', $data)
        ) {
            $ing = round((float) $data['total_ingresos'], 2);
            $ded = round((float) $data['total_deducciones'], 2);
            $net = round((float) $data['salario_neto'], 2);

            if (round($ing - $ded, 2) !== $net) {
                throw new InvalidArgumentException('salario_neto debe ser igual a total_ingresos menos total_deducciones.');
            }
        }

        foreach (['dias_trabajados', 'dias_ausentes'] as $field) {
            if (array_key_exists($field, $data)) {
                $value = round((float) $data[$field], 2);
                if ($value < 0) {
                    throw new InvalidArgumentException($field . ' no puede ser negativo.');
                }
            }
        }

        if (array_key_exists('horas_extras', $data)) {
            $value = round((float) $data['horas_extras'], 2);
            if ($value < 0) {
                throw new InvalidArgumentException('horas_extras no puede ser negativo.');
            }
        }

        if (array_key_exists('estado_pago', $data)) {
            $estadoPago = strtoupper(trim((string) $data['estado_pago']));
            if ($estadoPago === '') {
                throw new InvalidArgumentException('estado_pago no puede estar vacio.');
            }
            $this->validateEstadoPago($estadoPago);
        }

        if (array_key_exists('fecha_pago', $data) && $data['fecha_pago'] !== null) {
            $fecha = trim((string) $data['fecha_pago']);
            if ($fecha !== '' && !$this->isValidDate($fecha)) {
                throw new InvalidArgumentException('fecha_pago debe tener formato Y-m-d.');
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

        foreach (['nomina_id', 'personal_id'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = (int) $payload[$field];
            }
        }

        foreach (['salario_base', 'total_ingresos', 'total_deducciones', 'salario_neto', 'dias_trabajados', 'dias_ausentes', 'horas_extras'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = round((float) $payload[$field], 2);
            }
        }

        if (array_key_exists('estado_pago', $payload)) {
            $payload['estado_pago'] = strtoupper(trim((string) $payload['estado_pago']));
        }

        if (array_key_exists('fecha_pago', $payload) && $payload['fecha_pago'] !== null) {
            $fecha = trim((string) $payload['fecha_pago']);
            $payload['fecha_pago'] = $fecha === '' ? null : $fecha;
        }

        if (array_key_exists('observaciones', $payload) && $payload['observaciones'] !== null) {
            $obs = trim((string) $payload['observaciones']);
            $payload['observaciones'] = $obs === '' ? null : $obs;
        }

        return $payload;
    }

    private function validateEstadoPago(string $estadoPago): void
    {
        $permitidos = [self::ESTADO_PENDIENTE, self::ESTADO_PAGADO, self::ESTADO_ANULADO];
        if (!in_array($estadoPago, $permitidos, true)) {
            throw new InvalidArgumentException('estado_pago no es valido para nomina_detalles.');
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
