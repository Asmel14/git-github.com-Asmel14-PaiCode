<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class NominasModel extends BaseModel
{
    protected string $table = 'nominas';

    protected array $primaryKey = ['id'];

    private const ESTADO_BORRADOR = 'BORRADOR';
    private const ESTADO_PROCESADA = 'PROCESADA';
    private const ESTADO_PAGADA = 'PAGADA';
    private const ESTADO_ANULADA = 'ANULADA';

    protected array $fillable = [
        'periodo_nomina_id',
        'numero_nomina',
        'fecha_proceso',
        'total_ingresos',
        'total_deducciones',
        'total_neto',
        'estado',
        'observaciones',
        'usuario_id',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByNumeroNomina(string $numeroNomina): ?array
    {
        return $this->find(['numero_nomina' => trim($numeroNomina)]);
    }

    public function getByPeriodoId(int $periodoNominaId, int $limit = 500): array
    {
        $this->validatePositiveId($periodoNominaId, 'periodo_nomina_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `nominas`
                WHERE `periodo_nomina_id` = :periodo_id
                ORDER BY `fecha_proceso` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':periodo_id', $periodoNominaId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByEstado(string $estado, int $limit = 500): array
    {
        $estadoNormalizado = strtoupper(trim($estado));
        $this->validateEstado($estadoNormalizado);
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `nominas`
                WHERE `estado` = :estado
                ORDER BY `fecha_proceso` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estado', $estadoNormalizado);
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
        $sql = 'SELECT * FROM `nominas`
                WHERE `fecha_proceso` >= :fecha_inicio AND `fecha_proceso` <= :fecha_fin
                ORDER BY `fecha_proceso` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':fecha_inicio', $fechaInicio);
        $stmt->bindValue(':fecha_fin', $fechaFin);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createNomina(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        if ($this->getByNumeroNomina((string) $payload['numero_nomina']) !== null) {
            throw new InvalidArgumentException('Ya existe una nomina con ese numero_nomina.');
        }

        if (!array_key_exists('estado', $payload)) {
            $payload['estado'] = self::ESTADO_BORRADOR;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear la nomina.');
        }

        return $newId;
    }

    public function updateNomina(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('La nomina indicada no existe.');
        }

        $payload = $this->normalizePayload($data);

        if (array_key_exists('numero_nomina', $payload)) {
            $existing = $this->getByNumeroNomina((string) $payload['numero_nomina']);
            if ($existing !== null && (int) $existing['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe una nomina con ese numero_nomina.');
            }
        }

        return $this->update(['id' => $id], $payload);
    }

    public function cambiarEstado(int $id, string $estado): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('La nomina indicada no existe.');
        }

        $estadoNormalizado = strtoupper(trim($estado));
        $this->validateEstado($estadoNormalizado);

        if (($current['estado'] ?? null) === $estadoNormalizado) {
            return 0;
        }

        return $this->update(['id' => $id], ['estado' => $estadoNormalizado]);
    }

    public function marcarProcesada(int $id): int
    {
        return $this->cambiarEstado($id, self::ESTADO_PROCESADA);
    }

    public function marcarPagada(int $id): int
    {
        return $this->cambiarEstado($id, self::ESTADO_PAGADA);
    }

    public function anularNomina(int $id, ?string $observaciones = null): int
    {
        $payload = ['estado' => self::ESTADO_ANULADA];
        if ($observaciones !== null) {
            $obs = trim($observaciones);
            $payload['observaciones'] = $obs === '' ? null : $obs;
        }

        $this->validateData($payload, true);
        return $this->updateNomina($id, $payload);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['periodo_nomina_id', 'numero_nomina', 'total_ingresos', 'total_deducciones', 'total_neto'];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        if (array_key_exists('periodo_nomina_id', $data)) {
            $this->validatePositiveId((int) $data['periodo_nomina_id'], 'periodo_nomina_id');
        }

        if (array_key_exists('numero_nomina', $data)) {
            $numero = trim((string) $data['numero_nomina']);
            if ($numero === '') {
                throw new InvalidArgumentException('numero_nomina no puede estar vacio.');
            }
            if (mb_strlen($numero) > 50) {
                throw new InvalidArgumentException('numero_nomina no puede exceder 50 caracteres.');
            }
        }

        foreach (['total_ingresos', 'total_deducciones', 'total_neto'] as $field) {
            if (array_key_exists($field, $data)) {
                $monto = round((float) $data[$field], 2);
                if ($monto < 0) {
                    throw new InvalidArgumentException($field . ' no puede ser negativo.');
                }
            }
        }

        if (
            array_key_exists('total_ingresos', $data)
            && array_key_exists('total_deducciones', $data)
            && array_key_exists('total_neto', $data)
        ) {
            $ingresos = round((float) $data['total_ingresos'], 2);
            $deducciones = round((float) $data['total_deducciones'], 2);
            $neto = round((float) $data['total_neto'], 2);
            if (round($ingresos - $deducciones, 2) !== $neto) {
                throw new InvalidArgumentException('total_neto debe ser igual a total_ingresos menos total_deducciones.');
            }
        }

        if (array_key_exists('fecha_proceso', $data) && $data['fecha_proceso'] !== null) {
            $fecha = trim((string) $data['fecha_proceso']);
            if ($fecha !== '' && !$this->isValidDateTime($fecha)) {
                throw new InvalidArgumentException('fecha_proceso debe tener formato Y-m-d H:i:s.');
            }
        }

        if (array_key_exists('estado', $data)) {
            $estado = strtoupper(trim((string) $data['estado']));
            if ($estado === '') {
                throw new InvalidArgumentException('estado no puede estar vacio.');
            }
            $this->validateEstado($estado);
        }

        if (array_key_exists('usuario_id', $data) && $data['usuario_id'] !== null) {
            $this->validatePositiveId((int) $data['usuario_id'], 'usuario_id');
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

        foreach (['periodo_nomina_id', 'usuario_id'] as $field) {
            if (array_key_exists($field, $payload) && $payload[$field] !== null) {
                $payload[$field] = (int) $payload[$field];
            }
        }

        if (array_key_exists('numero_nomina', $payload)) {
            $payload['numero_nomina'] = trim((string) $payload['numero_nomina']);
        }

        foreach (['total_ingresos', 'total_deducciones', 'total_neto'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = round((float) $payload[$field], 2);
            }
        }

        if (array_key_exists('fecha_proceso', $payload) && $payload['fecha_proceso'] !== null) {
            $fecha = trim((string) $payload['fecha_proceso']);
            $payload['fecha_proceso'] = $fecha === '' ? null : $fecha;
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
            self::ESTADO_BORRADOR,
            self::ESTADO_PROCESADA,
            self::ESTADO_PAGADA,
            self::ESTADO_ANULADA,
        ];

        if (!in_array($estado, $permitidos, true)) {
            throw new InvalidArgumentException('estado no es valido para nominas.');
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
