<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class DatosCentroEducativoModel extends BaseModel
{
    protected string $table = 'datos_centro_educativo';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'nombre_centro',
        'codigo_centro',
        'rnc',
        'telefono',
        'celular',
        'correo_electronico',
        'lema',
        'direccion',
        'logo',
        'estado',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getPrincipal(): ?array
    {
        $sql = 'SELECT * FROM `datos_centro_educativo` ORDER BY `id` ASC LIMIT 1';
        $stmt = $this->db->query($sql);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    public function getActivos(): array
    {
        $sql = 'SELECT * FROM `datos_centro_educativo` WHERE `estado` = 1 ORDER BY `id` ASC';
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    public function createRegistro(array $data): int
    {
        $this->validateData($data);

        $payload = $this->normalizePayload($data);
        if (!array_key_exists('estado', $payload)) {
            $payload['estado'] = 1;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el registro del centro educativo.');
        }

        return $newId;
    }

    public function updateRegistro(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El registro indicado no existe.');
        }

        $payload = $this->normalizePayload($data);
        return $this->update(['id' => $id], $payload);
    }

    public function upsertPrincipal(array $data): int
    {
        $principal = $this->getPrincipal();
        if ($principal === null) {
            return $this->createRegistro($data);
        }

        $this->updateRegistro((int) $principal['id'], $data);
        return (int) $principal['id'];
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        if (!$isPartial) {
            if (!array_key_exists('nombre_centro', $data) || trim((string) $data['nombre_centro']) === '') {
                throw new InvalidArgumentException('El campo nombre_centro es obligatorio.');
            }
        }

        $rules = [
            'nombre_centro' => 255,
            'codigo_centro' => 50,
            'rnc' => 30,
            'telefono' => 30,
            'celular' => 30,
            'correo_electronico' => 150,
            'lema' => 255,
            'direccion' => 255,
            'logo' => 255,
        ];

        foreach ($rules as $field => $maxLength) {
            if (!array_key_exists($field, $data) || $data[$field] === null) {
                continue;
            }

            $value = trim((string) $data[$field]);
            if ($field === 'nombre_centro' && $value === '') {
                throw new InvalidArgumentException('nombre_centro no puede estar vacio.');
            }

            if ($value !== '' && mb_strlen($value) > $maxLength) {
                throw new InvalidArgumentException($field . ' no puede exceder ' . $maxLength . ' caracteres.');
            }
        }

        if (array_key_exists('correo_electronico', $data) && $data['correo_electronico'] !== null) {
            $correo = trim((string) $data['correo_electronico']);
            if ($correo !== '' && filter_var($correo, FILTER_VALIDATE_EMAIL) === false) {
                throw new InvalidArgumentException('correo_electronico no tiene un formato valido.');
            }
        }

        if (array_key_exists('estado', $data)) {
            $estado = (int) $data['estado'];
            if (!in_array($estado, [0, 1], true)) {
                throw new InvalidArgumentException('estado solo permite 0 o 1.');
            }
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;
        $textFields = [
            'nombre_centro',
            'codigo_centro',
            'rnc',
            'telefono',
            'celular',
            'correo_electronico',
            'lema',
            'direccion',
            'logo',
        ];

        foreach ($textFields as $field) {
            if (!array_key_exists($field, $payload) || $payload[$field] === null) {
                continue;
            }

            $value = trim((string) $payload[$field]);
            $payload[$field] = $value === '' && $field !== 'nombre_centro' ? null : $value;
        }

        if (array_key_exists('correo_electronico', $payload) && $payload['correo_electronico'] !== null) {
            $payload['correo_electronico'] = strtolower((string) $payload['correo_electronico']);
        }

        if (array_key_exists('estado', $payload)) {
            $payload['estado'] = (int) $payload['estado'];
        }

        return $payload;
    }
}
