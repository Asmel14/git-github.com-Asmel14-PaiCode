<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class TandasModel extends BaseModel
{
    protected string $table = 'tandas';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'nombre',
        'codigo',
        'hora_inicio',
        'hora_fin',
        'estado',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByNombre(string $nombre): ?array
    {
        return $this->find(['nombre' => trim($nombre)]);
    }

    public function getByCodigo(string $codigo): ?array
    {
        return $this->find(['codigo' => strtoupper(trim($codigo))]);
    }

    public function getActivas(int $limit = 500): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `tandas`
                WHERE `estado` = 1
                ORDER BY `nombre` ASC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createTanda(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        if ($this->getByNombre((string) $payload['nombre']) !== null) {
            throw new InvalidArgumentException('Ya existe una tanda con ese nombre.');
        }

        if ($this->getByCodigo((string) $payload['codigo']) !== null) {
            throw new InvalidArgumentException('Ya existe una tanda con ese codigo.');
        }

        if (!array_key_exists('estado', $payload)) {
            $payload['estado'] = 1;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear la tanda.');
        }

        return $newId;
    }

    public function updateTanda(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('La tanda indicada no existe.');
        }

        $payload = $this->normalizePayload($data);

        if (array_key_exists('nombre', $payload)) {
            $exists = $this->getByNombre((string) $payload['nombre']);
            if ($exists !== null && (int) $exists['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe otra tanda con ese nombre.');
            }
        }

        if (array_key_exists('codigo', $payload)) {
            $exists = $this->getByCodigo((string) $payload['codigo']);
            if ($exists !== null && (int) $exists['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe otra tanda con ese codigo.');
            }
        }

        return $this->update(['id' => $id], $payload);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        if (!$isPartial) {
            foreach (['nombre', 'codigo'] as $field) {
                if (!array_key_exists($field, $data)) {
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
            if (!preg_match('/^[A-Z0-9_]+$/', $codigo)) {
                throw new InvalidArgumentException('codigo solo permite letras, numeros y guion bajo.');
            }
            if (mb_strlen($codigo) > 30) {
                throw new InvalidArgumentException('codigo no puede exceder 30 caracteres.');
            }
        }

        foreach (['hora_inicio', 'hora_fin'] as $field) {
            if (array_key_exists($field, $data) && $data[$field] !== null && trim((string) $data[$field]) !== '') {
                if (!$this->isValidTime((string) $data[$field])) {
                    throw new InvalidArgumentException($field . ' debe tener formato HH:MM:SS.');
                }
            }
        }

        if (
            array_key_exists('hora_inicio', $data) &&
            array_key_exists('hora_fin', $data) &&
            trim((string) ($data['hora_inicio'] ?? '')) !== '' &&
            trim((string) ($data['hora_fin'] ?? '')) !== ''
        ) {
            $ini = (string) $data['hora_inicio'];
            $fin = (string) $data['hora_fin'];
            if ($ini >= $fin) {
                throw new InvalidArgumentException('hora_inicio debe ser menor que hora_fin.');
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

        if (array_key_exists('nombre', $payload)) {
            $payload['nombre'] = trim((string) $payload['nombre']);
        }

        if (array_key_exists('codigo', $payload)) {
            $payload['codigo'] = strtoupper(trim((string) $payload['codigo']));
        }

        foreach (['hora_inicio', 'hora_fin'] as $field) {
            if (array_key_exists($field, $payload)) {
                $v = trim((string) $payload[$field]);
                $payload[$field] = $v === '' ? null : $v;
            }
        }

        if (array_key_exists('estado', $payload)) {
            $payload['estado'] = (int) $payload['estado'];
        }

        return $payload;
    }

    private function isValidTime(string $time): bool
    {
        $dt = DateTime::createFromFormat('H:i:s', $time);
        return $dt !== false && $dt->format('H:i:s') === $time;
    }

    private function normalizeLimit(int $limit): int
    {
        if ($limit < 1) {
            return 1;
        }
        if ($limit > 5000) {
            return 5000;
        }

        return $limit;
    }
}
