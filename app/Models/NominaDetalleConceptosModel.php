<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class NominaDetalleConceptosModel extends BaseModel
{
    protected string $table = 'nomina_detalle_conceptos';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'nomina_detalle_id',
        'concepto_id',
        'descripcion',
        'cantidad',
        'porcentaje',
        'valor',
        'total',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByNominaDetalleId(int $nominaDetalleId): array
    {
        $this->validatePositiveId($nominaDetalleId, 'nomina_detalle_id');
        $sql = 'SELECT * FROM `nomina_detalle_conceptos`
                WHERE `nomina_detalle_id` = :nomina_detalle_id
                ORDER BY `id` ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nomina_detalle_id', $nominaDetalleId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByConceptoId(int $conceptoId, int $limit = 1000): array
    {
        $this->validatePositiveId($conceptoId, 'concepto_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `nomina_detalle_conceptos`
                WHERE `concepto_id` = :concepto_id
                ORDER BY `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':concepto_id', $conceptoId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByDetalleAndConcepto(int $nominaDetalleId, int $conceptoId): array
    {
        $this->validatePositiveId($nominaDetalleId, 'nomina_detalle_id');
        $this->validatePositiveId($conceptoId, 'concepto_id');

        $sql = 'SELECT * FROM `nomina_detalle_conceptos`
                WHERE `nomina_detalle_id` = :nomina_detalle_id
                  AND `concepto_id` = :concepto_id
                ORDER BY `id` ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nomina_detalle_id', $nominaDetalleId, PDO::PARAM_INT);
        $stmt->bindValue(':concepto_id', $conceptoId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getTotalByNominaDetalleId(int $nominaDetalleId): float
    {
        $this->validatePositiveId($nominaDetalleId, 'nomina_detalle_id');
        $sql = 'SELECT COALESCE(SUM(`total`), 0) AS total_sum
                FROM `nomina_detalle_conceptos`
                WHERE `nomina_detalle_id` = :nomina_detalle_id';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nomina_detalle_id', $nominaDetalleId, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();
        return round((float) ($row['total_sum'] ?? 0), 2);
    }

    public function createConceptoDetalle(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el concepto del detalle de nomina.');
        }

        return $newId;
    }

    public function updateConceptoDetalle(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El concepto de detalle indicado no existe.');
        }

        $payload = $this->normalizePayload($data);
        return $this->update(['id' => $id], $payload);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['nomina_detalle_id', 'concepto_id', 'cantidad', 'valor'];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        foreach (['nomina_detalle_id', 'concepto_id'] as $field) {
            if (array_key_exists($field, $data)) {
                $this->validatePositiveId((int) $data[$field], $field);
            }
        }

        if (array_key_exists('descripcion', $data) && $data['descripcion'] !== null) {
            $descripcion = trim((string) $data['descripcion']);
            if ($descripcion !== '' && mb_strlen($descripcion) > 255) {
                throw new InvalidArgumentException('descripcion no puede exceder 255 caracteres.');
            }
        }

        if (array_key_exists('cantidad', $data)) {
            $cantidad = round((float) $data['cantidad'], 2);
            if ($cantidad <= 0) {
                throw new InvalidArgumentException('cantidad debe ser mayor que cero.');
            }
        }

        if (array_key_exists('porcentaje', $data) && $data['porcentaje'] !== null) {
            $porcentaje = round((float) $data['porcentaje'], 4);
            if ($porcentaje < 0 || $porcentaje > 100) {
                throw new InvalidArgumentException('porcentaje debe estar entre 0 y 100.');
            }
        }

        foreach (['valor', 'total'] as $field) {
            if (array_key_exists($field, $data)) {
                $value = round((float) $data[$field], 2);
                if ($value < 0) {
                    throw new InvalidArgumentException($field . ' no puede ser negativo.');
                }
            }
        }

        if (array_key_exists('cantidad', $data) && array_key_exists('valor', $data) && array_key_exists('total', $data)) {
            $cantidad = round((float) $data['cantidad'], 2);
            $valor = round((float) $data['valor'], 2);
            $total = round((float) $data['total'], 2);
            if (round($cantidad * $valor, 2) !== $total) {
                throw new InvalidArgumentException('total debe ser igual a cantidad por valor.');
            }
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;

        foreach (['nomina_detalle_id', 'concepto_id'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = (int) $payload[$field];
            }
        }

        if (array_key_exists('descripcion', $payload) && $payload['descripcion'] !== null) {
            $descripcion = trim((string) $payload['descripcion']);
            $payload['descripcion'] = $descripcion === '' ? null : $descripcion;
        }

        if (array_key_exists('cantidad', $payload)) {
            $payload['cantidad'] = round((float) $payload['cantidad'], 2);
        }

        if (array_key_exists('porcentaje', $payload) && $payload['porcentaje'] !== null) {
            $payload['porcentaje'] = round((float) $payload['porcentaje'], 4);
        }

        if (array_key_exists('valor', $payload)) {
            $payload['valor'] = round((float) $payload['valor'], 2);
        }

        if (!array_key_exists('total', $payload) && array_key_exists('cantidad', $payload) && array_key_exists('valor', $payload)) {
            $payload['total'] = round((float) $payload['cantidad'] * (float) $payload['valor'], 2);
        } elseif (array_key_exists('total', $payload)) {
            $payload['total'] = round((float) $payload['total'], 2);
        }

        return $payload;
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
