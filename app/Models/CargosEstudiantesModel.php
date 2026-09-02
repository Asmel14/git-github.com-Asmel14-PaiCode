<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class CargosEstudiantesModel extends BaseModel
{
    protected string $table = 'cargos_estudiantes';

    protected array $primaryKey = ['id'];

    private const ESTADO_PENDIENTE = 'PENDIENTE';
    private const ESTADO_PARCIAL = 'PARCIAL';
    private const ESTADO_PAGADO = 'PAGADO';
    private const ESTADO_ANULADO = 'ANULADO';

    protected array $fillable = [
        'estudiante_id',
        'inscripcion_id',
        'concepto_id',
        'periodo_id',
        'descripcion',
        'fecha_emision',
        'fecha_vencimiento',
        'monto',
        'monto_pagado',
        'estado',
        'genera_mora',
        'mora_generada',
        'observaciones',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByEstudiante(int $estudianteId, int $limit = 500): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `cargos_estudiantes`
                WHERE `estudiante_id` = :estudiante_id
                ORDER BY `fecha_emision` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estudiante_id', $estudianteId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getPendientesByEstudiante(int $estudianteId): array
    {
        $sql = 'SELECT * FROM `cargos_estudiantes`
                WHERE `estudiante_id` = :estudiante_id
                  AND `estado` IN (:estado_pendiente, :estado_parcial)
                ORDER BY `fecha_vencimiento` ASC, `id` ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estudiante_id', $estudianteId, PDO::PARAM_INT);
        $stmt->bindValue(':estado_pendiente', self::ESTADO_PENDIENTE);
        $stmt->bindValue(':estado_parcial', self::ESTADO_PARCIAL);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getVencidosPendientes(?string $fechaCorte = null): array
    {
        $fecha = $fechaCorte ?? date('Y-m-d');
        if (!$this->isValidDate($fecha)) {
            throw new InvalidArgumentException('fechaCorte debe tener formato Y-m-d.');
        }

        $sql = 'SELECT * FROM `cargos_estudiantes`
                WHERE `fecha_vencimiento` < :fecha
                  AND `estado` IN (:estado_pendiente, :estado_parcial)
                ORDER BY `fecha_vencimiento` ASC, `id` ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':fecha', $fecha);
        $stmt->bindValue(':estado_pendiente', self::ESTADO_PENDIENTE);
        $stmt->bindValue(':estado_parcial', self::ESTADO_PARCIAL);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createCargo(array $data): int
    {
        $this->validateData($data);

        $payload = $data;
        $payload['monto'] = $this->normalizeAmount($payload['monto']);
        $payload['monto_pagado'] = isset($payload['monto_pagado'])
            ? $this->normalizeAmount($payload['monto_pagado'])
            : 0.0;

        if (empty($payload['estado'])) {
            $payload['estado'] = $this->resolveEstado($payload['monto'], $payload['monto_pagado']);
        }

        $payload['genera_mora'] = isset($payload['genera_mora']) ? (int) $payload['genera_mora'] : 0;
        $payload['mora_generada'] = isset($payload['mora_generada']) ? (int) $payload['mora_generada'] : 0;

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el cargo del estudiante.');
        }

        return $newId;
    }

    public function registrarPago(int $cargoId, float $montoPago): int
    {
        $cargo = $this->getById($cargoId);
        if ($cargo === null) {
            throw new InvalidArgumentException('El cargo indicado no existe.');
        }

        if (($cargo['estado'] ?? null) === self::ESTADO_ANULADO) {
            throw new InvalidArgumentException('No se puede pagar un cargo anulado.');
        }

        $monto = $this->normalizeAmount($cargo['monto'] ?? 0);
        $pagadoActual = $this->normalizeAmount($cargo['monto_pagado'] ?? 0);
        $pago = $this->normalizeAmount($montoPago);

        if ($pago <= 0) {
            throw new InvalidArgumentException('montoPago debe ser mayor que cero.');
        }

        $nuevoPagado = $this->normalizeAmount($pagadoActual + $pago);
        if ($nuevoPagado > $monto) {
            throw new InvalidArgumentException('El pago excede el monto pendiente del cargo.');
        }

        $nuevoEstado = $this->resolveEstado($monto, $nuevoPagado);

        return $this->update(['id' => $cargoId], [
            'monto_pagado' => $nuevoPagado,
            'estado' => $nuevoEstado,
        ]);
    }

    public function anularCargo(int $cargoId, ?string $observaciones = null): int
    {
        $cargo = $this->getById($cargoId);
        if ($cargo === null) {
            throw new InvalidArgumentException('El cargo indicado no existe.');
        }

        if (($cargo['estado'] ?? null) === self::ESTADO_ANULADO) {
            return 0;
        }

        return $this->update(['id' => $cargoId], [
            'estado' => self::ESTADO_ANULADO,
            'observaciones' => $observaciones,
        ]);
    }

    private function validateData(array $data): void
    {
        $required = [
            'estudiante_id',
            'concepto_id',
            'descripcion',
            'fecha_emision',
            'fecha_vencimiento',
            'monto',
        ];

        foreach ($required as $field) {
            if (!array_key_exists($field, $data)) {
                throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
            }
        }

        foreach (['estudiante_id', 'concepto_id'] as $field) {
            if ((int) $data[$field] <= 0) {
                throw new InvalidArgumentException('El campo ' . $field . ' debe ser mayor que cero.');
            }
        }

        if (array_key_exists('inscripcion_id', $data) && $data['inscripcion_id'] !== null && (int) $data['inscripcion_id'] <= 0) {
            throw new InvalidArgumentException('inscripcion_id debe ser mayor que cero o null.');
        }

        if (array_key_exists('periodo_id', $data) && $data['periodo_id'] !== null && (int) $data['periodo_id'] <= 0) {
            throw new InvalidArgumentException('periodo_id debe ser mayor que cero o null.');
        }

        if (trim((string) $data['descripcion']) === '') {
            throw new InvalidArgumentException('descripcion no puede estar vacia.');
        }

        if (mb_strlen(trim((string) $data['descripcion'])) > 255) {
            throw new InvalidArgumentException('descripcion no puede exceder 255 caracteres.');
        }

        $fechaEmision = (string) $data['fecha_emision'];
        $fechaVencimiento = (string) $data['fecha_vencimiento'];
        if (!$this->isValidDate($fechaEmision) || !$this->isValidDate($fechaVencimiento)) {
            throw new InvalidArgumentException('fecha_emision y fecha_vencimiento deben tener formato Y-m-d.');
        }

        if ($fechaEmision > $fechaVencimiento) {
            throw new InvalidArgumentException('fecha_emision no puede ser mayor que fecha_vencimiento.');
        }

        $monto = $this->normalizeAmount($data['monto']);
        if ($monto <= 0) {
            throw new InvalidArgumentException('monto debe ser mayor que cero.');
        }

        if (array_key_exists('monto_pagado', $data)) {
            $montoPagado = $this->normalizeAmount($data['monto_pagado']);
            if ($montoPagado < 0) {
                throw new InvalidArgumentException('monto_pagado no puede ser negativo.');
            }
            if ($montoPagado > $monto) {
                throw new InvalidArgumentException('monto_pagado no puede ser mayor que monto.');
            }
        }

        if (array_key_exists('estado', $data) && $data['estado'] !== null && $data['estado'] !== '') {
            $estado = (string) $data['estado'];
            $permitidos = [
                self::ESTADO_PENDIENTE,
                self::ESTADO_PARCIAL,
                self::ESTADO_PAGADO,
                self::ESTADO_ANULADO,
            ];

            if (!in_array($estado, $permitidos, true)) {
                throw new InvalidArgumentException('estado no es valido para cargos_estudiantes.');
            }
        }

        foreach (['genera_mora', 'mora_generada'] as $field) {
            if (array_key_exists($field, $data)) {
                $value = (int) $data[$field];
                if (!in_array($value, [0, 1], true)) {
                    throw new InvalidArgumentException($field . ' solo permite 0 o 1.');
                }
            }
        }
    }

    private function resolveEstado(float $monto, float $montoPagado): string
    {
        if ($montoPagado <= 0) {
            return self::ESTADO_PENDIENTE;
        }

        if ($montoPagado >= $monto) {
            return self::ESTADO_PAGADO;
        }

        return self::ESTADO_PARCIAL;
    }

    private function normalizeAmount(mixed $value): float
    {
        return round((float) $value, 2);
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

        if ($limit > 2000) {
            return 2000;
        }

        return $limit;
    }
}
