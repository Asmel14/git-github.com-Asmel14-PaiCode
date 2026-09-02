<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class DireccionesEstudiantesModel extends BaseModel
{
    protected string $table = 'direcciones_estudiantes';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'estudiante_id',
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

    public function getByEstudianteId(int $estudianteId): ?array
    {
        return $this->find(['estudiante_id' => $estudianteId]);
    }

    public function createDireccion(array $data): int
    {
        $this->validateData($data);

        $estudianteId = (int) $data['estudiante_id'];
        if ($this->getByEstudianteId($estudianteId) !== null) {
            throw new InvalidArgumentException('Ya existe una direccion para ese estudiante.');
        }

        $payload = $this->normalizePayload($data);

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear la direccion del estudiante.');
        }

        return $newId;
    }

    public function updateByEstudianteId(int $estudianteId, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getByEstudianteId($estudianteId);
        if ($current === null) {
            throw new InvalidArgumentException('No existe direccion para el estudiante indicado.');
        }

        $payload = $this->normalizePayload($data);
        return $this->update(['estudiante_id' => $estudianteId], $payload);
    }

    public function upsertByEstudianteId(int $estudianteId, array $data): int
    {
        $current = $this->getByEstudianteId($estudianteId);
        if ($current === null) {
            $data['estudiante_id'] = $estudianteId;
            return $this->createDireccion($data);
        }

        $this->updateByEstudianteId($estudianteId, $data);
        return (int) $current['id'];
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        if (!$isPartial) {
            if (!array_key_exists('estudiante_id', $data) || (int) $data['estudiante_id'] <= 0) {
                throw new InvalidArgumentException('El campo estudiante_id es obligatorio y debe ser mayor que cero.');
            }
        }

        if (array_key_exists('estudiante_id', $data) && (int) $data['estudiante_id'] <= 0) {
            throw new InvalidArgumentException('estudiante_id debe ser mayor que cero.');
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

        if (array_key_exists('estudiante_id', $payload)) {
            $payload['estudiante_id'] = (int) $payload['estudiante_id'];
        }

        return $payload;
    }
}
