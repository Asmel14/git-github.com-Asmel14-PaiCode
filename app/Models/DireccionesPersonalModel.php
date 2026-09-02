<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class DireccionesPersonalModel extends BaseModel
{
    protected string $table = 'direcciones_personal';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'personal_id',
        'provincia',
        'municipio',
        'distrito_municipal',
        'seccion',
        'barrio',
        'sub_barrio',
        'calle_numero',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByPersonalId(int $personalId): ?array
    {
        return $this->find(['personal_id' => $personalId]);
    }

    public function createDireccion(array $data): int
    {
        $this->validateData($data);

        $personalId = (int) $data['personal_id'];
        if ($this->getByPersonalId($personalId) !== null) {
            throw new InvalidArgumentException('Ya existe una direccion para ese personal.');
        }

        $payload = $this->normalizePayload($data);

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear la direccion del personal.');
        }

        return $newId;
    }

    public function updateByPersonalId(int $personalId, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getByPersonalId($personalId);
        if ($current === null) {
            throw new InvalidArgumentException('No existe direccion para el personal indicado.');
        }

        $payload = $this->normalizePayload($data);
        return $this->update(['personal_id' => $personalId], $payload);
    }

    public function upsertByPersonalId(int $personalId, array $data): int
    {
        $current = $this->getByPersonalId($personalId);
        if ($current === null) {
            $data['personal_id'] = $personalId;
            return $this->createDireccion($data);
        }

        $this->updateByPersonalId($personalId, $data);
        return (int) $current['id'];
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        if (!$isPartial) {
            if (!array_key_exists('personal_id', $data) || (int) $data['personal_id'] <= 0) {
                throw new InvalidArgumentException('El campo personal_id es obligatorio y debe ser mayor que cero.');
            }
        }

        if (array_key_exists('personal_id', $data) && (int) $data['personal_id'] <= 0) {
            throw new InvalidArgumentException('personal_id debe ser mayor que cero.');
        }

        $fields100 = [
            'provincia',
            'municipio',
            'distrito_municipal',
            'seccion',
            'barrio',
            'sub_barrio',
        ];

        foreach ($fields100 as $field) {
            if (!array_key_exists($field, $data) || $data[$field] === null) {
                continue;
            }

            $value = trim((string) $data[$field]);
            if ($value !== '' && mb_strlen($value) > 100) {
                throw new InvalidArgumentException($field . ' no puede exceder 100 caracteres.');
            }
        }

        if (array_key_exists('calle_numero', $data) && $data['calle_numero'] !== null) {
            $calle = trim((string) $data['calle_numero']);
            if ($calle !== '' && mb_strlen($calle) > 255) {
                throw new InvalidArgumentException('calle_numero no puede exceder 255 caracteres.');
            }
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;

        $textFields = [
            'provincia',
            'municipio',
            'distrito_municipal',
            'seccion',
            'barrio',
            'sub_barrio',
            'calle_numero',
        ];

        foreach ($textFields as $field) {
            if (!array_key_exists($field, $payload) || $payload[$field] === null) {
                continue;
            }

            $value = trim((string) $payload[$field]);
            $payload[$field] = $value === '' ? null : $value;
        }

        if (array_key_exists('personal_id', $payload)) {
            $payload['personal_id'] = (int) $payload['personal_id'];
        }

        return $payload;
    }
}
