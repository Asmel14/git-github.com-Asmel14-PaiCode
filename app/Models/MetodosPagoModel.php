<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class MetodosPagoModel extends BaseModel
{
    protected string $table = 'metodos_pago';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'nombre',
        'codigo',
        'requiere_referencia',
        'activo',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByCodigo(string $codigo): ?array
    {
        return $this->find(['codigo' => strtoupper(trim($codigo))]);
    }

    public function getByNombre(string $nombre): ?array
    {
        return $this->find(['nombre' => trim($nombre)]);
    }

    public function getActivos(): array
    {
        $sql = 'SELECT * FROM `metodos_pago` WHERE `activo` = 1 ORDER BY `nombre` ASC';
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    public function getAllOrdered(int $limit = 500): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `metodos_pago` ORDER BY `nombre` ASC, `id` DESC LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getQueRequierenReferencia(bool $soloActivos = true): array
    {
        $sql = 'SELECT * FROM `metodos_pago` WHERE `requiere_referencia` = 1';
        if ($soloActivos) {
            $sql .= ' AND `activo` = 1';
        }
        $sql .= ' ORDER BY `nombre` ASC';

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function createMetodo(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        if ($this->getByNombre((string) $payload['nombre']) !== null) {
            throw new InvalidArgumentException('Ya existe un metodo de pago con ese nombre.');
        }

        if ($this->getByCodigo((string) $payload['codigo']) !== null) {
            throw new InvalidArgumentException('Ya existe un metodo de pago con ese codigo.');
        }

        if (!array_key_exists('requiere_referencia', $payload)) {
            $payload['requiere_referencia'] = 0;
        }

        if (!array_key_exists('activo', $payload)) {
            $payload['activo'] = 1;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el metodo de pago.');
        }

        return $newId;
    }

    public function updateMetodo(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El metodo de pago indicado no existe.');
        }

        $payload = $this->normalizePayload($data);

        if (array_key_exists('nombre', $payload)) {
            $existingByName = $this->getByNombre((string) $payload['nombre']);
            if ($existingByName !== null && (int) $existingByName['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe un metodo de pago con ese nombre.');
            }
        }

        if (array_key_exists('codigo', $payload)) {
            $existingByCode = $this->getByCodigo((string) $payload['codigo']);
            if ($existingByCode !== null && (int) $existingByCode['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe un metodo de pago con ese codigo.');
            }
        }

        return $this->update(['id' => $id], $payload);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['nombre', 'codigo'];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data) || trim((string) $data[$field]) === '') {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        if (array_key_exists('nombre', $data)) {
            $nombre = trim((string) $data['nombre']);
            if ($nombre === '') {
                throw new InvalidArgumentException('nombre no puede estar vacio.');
            }
            if (mb_strlen($nombre) > 100) {
                throw new InvalidArgumentException('nombre no puede exceder 100 caracteres.');
            }
        }

        if (array_key_exists('codigo', $data)) {
            $codigo = strtoupper(trim((string) $data['codigo']));
            if ($codigo === '') {
                throw new InvalidArgumentException('codigo no puede estar vacio.');
            }
            if (mb_strlen($codigo) > 30) {
                throw new InvalidArgumentException('codigo no puede exceder 30 caracteres.');
            }
        }

        foreach (['requiere_referencia', 'activo'] as $field) {
            if (array_key_exists($field, $data)) {
                $value = (int) $data[$field];
                if (!in_array($value, [0, 1], true)) {
                    throw new InvalidArgumentException($field . ' solo permite 0 o 1.');
                }
            }
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;

        if (array_key_exists('nombre', $payload)) {
            $payload['nombre'] = trim((string) $payload['nombre']);
        }

        if (array_key_exists('codigo', $payload)) {
            $payload['codigo'] = strtoupper(trim((string) $payload['codigo']));
        }

        foreach (['requiere_referencia', 'activo'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = (int) $payload[$field];
            }
        }

        return $payload;
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
