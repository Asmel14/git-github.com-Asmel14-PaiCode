<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class PagoDetallesModel extends BaseModel
{
    protected string $table = 'pago_detalles';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'pago_id',
        'cargo_id',
        'monto',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByPagoId(int $pagoId): array
    {
        $this->validatePositiveId($pagoId, 'pago_id');
        $sql = 'SELECT * FROM `pago_detalles` WHERE `pago_id` = :pago_id ORDER BY `id` ASC';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':pago_id', $pagoId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByCargoId(int $cargoId): array
    {
        $this->validatePositiveId($cargoId, 'cargo_id');
        $sql = 'SELECT * FROM `pago_detalles` WHERE `cargo_id` = :cargo_id ORDER BY `id` DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':cargo_id', $cargoId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByPagoAndCargo(int $pagoId, int $cargoId): ?array
    {
        $this->validatePositiveId($pagoId, 'pago_id');
        $this->validatePositiveId($cargoId, 'cargo_id');

        return $this->find([
            'pago_id' => $pagoId,
            'cargo_id' => $cargoId,
        ]);
    }

    public function getTotalByPagoId(int $pagoId): float
    {
        $this->validatePositiveId($pagoId, 'pago_id');
        $sql = 'SELECT COALESCE(SUM(`monto`), 0) AS total FROM `pago_detalles` WHERE `pago_id` = :pago_id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':pago_id', $pagoId, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();
        return isset($row['total']) ? round((float) $row['total'], 2) : 0.0;
    }

    public function createDetalle(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        $exists = $this->getByPagoAndCargo((int) $payload['pago_id'], (int) $payload['cargo_id']);
        if ($exists !== null) {
            throw new InvalidArgumentException('Ya existe un detalle para la combinacion pago-cargo indicada.');
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el detalle de pago.');
        }

        return $newId;
    }

    public function updateDetalle(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El detalle de pago indicado no existe.');
        }

        $payload = $this->normalizePayload($data);

        $targetPagoId = array_key_exists('pago_id', $payload) ? (int) $payload['pago_id'] : (int) $current['pago_id'];
        $targetCargoId = array_key_exists('cargo_id', $payload) ? (int) $payload['cargo_id'] : (int) $current['cargo_id'];
        $duplicate = $this->getByPagoAndCargo($targetPagoId, $targetCargoId);
        if ($duplicate !== null && (int) $duplicate['id'] !== $id) {
            throw new InvalidArgumentException('Ya existe otro detalle con la combinacion pago-cargo indicada.');
        }

        return $this->update(['id' => $id], $payload);
    }

    public function deleteByPagoId(int $pagoId): int
    {
        $this->validatePositiveId($pagoId, 'pago_id');
        return $this->delete(['pago_id' => $pagoId]);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['pago_id', 'cargo_id', 'monto'];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        foreach (['pago_id', 'cargo_id'] as $field) {
            if (array_key_exists($field, $data)) {
                $this->validatePositiveId((int) $data[$field], $field);
            }
        }

        if (array_key_exists('monto', $data)) {
            $monto = round((float) $data['monto'], 2);
            if ($monto <= 0) {
                throw new InvalidArgumentException('monto debe ser mayor que cero.');
            }
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;

        foreach (['pago_id', 'cargo_id'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = (int) $payload[$field];
            }
        }

        if (array_key_exists('monto', $payload)) {
            $payload['monto'] = round((float) $payload['monto'], 2);
        }

        return $payload;
    }

    private function validatePositiveId(int $id, string $field): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException($field . ' debe ser mayor que cero.');
        }
    }
}
