<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class TarifariosModel extends BaseModel
{
    protected string $table = 'tarifarios';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'anio_escolar_id',
        'nombre',
        'estado',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByAnioEscolarId(int $anioEscolarId, int $limit = 500): array
    {
        $this->validatePositiveId($anioEscolarId, 'anio_escolar_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `tarifarios`
                WHERE `anio_escolar_id` = :anio_escolar_id
                ORDER BY `nombre` ASC, `id` DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':anio_escolar_id', $anioEscolarId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByNombre(string $nombre, ?int $anioEscolarId = null): ?array
    {
        $nombreNorm = trim($nombre);
        if ($nombreNorm === '') {
            throw new InvalidArgumentException('nombre no puede estar vacio.');
        }

        if ($anioEscolarId !== null) {
            $this->validatePositiveId($anioEscolarId, 'anio_escolar_id');
            return $this->find([
                'anio_escolar_id' => $anioEscolarId,
                'nombre' => $nombreNorm,
            ]);
        }

        return $this->find(['nombre' => $nombreNorm]);
    }

    public function getActivos(int $limit = 1000): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `tarifarios`
                WHERE `estado` = 1
                ORDER BY `anio_escolar_id` DESC, `nombre` ASC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createTarifario(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        $exists = $this->getByNombre((string) $payload['nombre'], (int) $payload['anio_escolar_id']);
        if ($exists !== null) {
            throw new InvalidArgumentException('Ya existe un tarifario con ese nombre para ese anio_escolar_id.');
        }

        if (!array_key_exists('estado', $payload)) {
            $payload['estado'] = 1;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el tarifario.');
        }

        return $newId;
    }

    public function updateTarifario(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El tarifario indicado no existe.');
        }

        $payload = $this->normalizePayload($data);

        if (array_key_exists('nombre', $payload) || array_key_exists('anio_escolar_id', $payload)) {
            $targetAnio = array_key_exists('anio_escolar_id', $payload)
                ? (int) $payload['anio_escolar_id']
                : (int) $current['anio_escolar_id'];
            $targetNombre = array_key_exists('nombre', $payload)
                ? (string) $payload['nombre']
                : (string) $current['nombre'];

            $exists = $this->getByNombre($targetNombre, $targetAnio);
            if ($exists !== null && (int) $exists['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe otro tarifario con ese nombre para ese anio_escolar_id.');
            }
        }

        return $this->update(['id' => $id], $payload);
    }

    public function activarTarifario(int $id): int
    {
        return $this->setEstado($id, 1);
    }

    public function desactivarTarifario(int $id): int
    {
        return $this->setEstado($id, 0);
    }

    private function setEstado(int $id, int $estado): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El tarifario indicado no existe.');
        }

        if ((int) ($current['estado'] ?? -1) === $estado) {
            return 0;
        }

        return $this->update(['id' => $id], ['estado' => $estado]);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['anio_escolar_id', 'nombre'];
        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        if (array_key_exists('anio_escolar_id', $data)) {
            $this->validatePositiveId((int) $data['anio_escolar_id'], 'anio_escolar_id');
        }

        if (array_key_exists('nombre', $data)) {
            $nombre = trim((string) $data['nombre']);
            if ($nombre === '') {
                throw new InvalidArgumentException('nombre no puede estar vacio.');
            }
            if (mb_strlen($nombre) > 150) {
                throw new InvalidArgumentException('nombre no puede exceder 150 caracteres.');
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

        if (array_key_exists('anio_escolar_id', $payload)) {
            $payload['anio_escolar_id'] = (int) $payload['anio_escolar_id'];
        }
        if (array_key_exists('nombre', $payload)) {
            $payload['nombre'] = trim((string) $payload['nombre']);
        }
        if (array_key_exists('estado', $payload)) {
            $payload['estado'] = (int) $payload['estado'];
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
        if ($limit > 5000) {
            return 5000;
        }

        return $limit;
    }
}
