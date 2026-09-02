<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class FamiliaresModel extends BaseModel
{
    protected string $table = 'familiares';

    protected array $primaryKey = ['id'];

    private const TIPO_MADRE = 'MADRE';
    private const TIPO_PADRE = 'PADRE';
    private const TIPO_TUTOR = 'TUTOR';

    protected array $fillable = [
        'tipo_familiar',
        'primer_nombre',
        'primer_apellido',
        'cedula',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByCedula(string $cedula): ?array
    {
        return $this->find(['cedula' => trim($cedula)]);
    }

    public function getByTipo(string $tipoFamiliar, int $limit = 300): array
    {
        $tipo = strtoupper(trim($tipoFamiliar));
        $this->validateTipoFamiliar($tipo);
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `familiares`
                WHERE `tipo_familiar` = :tipo_familiar
                ORDER BY `primer_apellido` ASC, `primer_nombre` ASC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':tipo_familiar', $tipo);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function searchByNombre(string $term, int $limit = 300): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $search = '%' . trim($term) . '%';

        $sql = 'SELECT * FROM `familiares`
                WHERE `primer_nombre` LIKE :term1
                   OR `primer_apellido` LIKE :term2
                ORDER BY `primer_apellido` ASC, `primer_nombre` ASC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':term1', $search);
        $stmt->bindValue(':term2', $search);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createFamiliar(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        if (array_key_exists('cedula', $payload) && $payload['cedula'] !== null) {
            $existing = $this->getByCedula((string) $payload['cedula']);
            if ($existing !== null) {
                throw new InvalidArgumentException('Ya existe un familiar con esa cedula.');
            }
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el familiar.');
        }

        return $newId;
    }

    public function updateFamiliar(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El familiar indicado no existe.');
        }

        $payload = $this->normalizePayload($data);

        if (array_key_exists('cedula', $payload) && $payload['cedula'] !== null) {
            $existing = $this->getByCedula((string) $payload['cedula']);
            if ($existing !== null && (int) $existing['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe un familiar con esa cedula.');
            }
        }

        return $this->update(['id' => $id], $payload);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['tipo_familiar', 'primer_nombre', 'primer_apellido'];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data) || trim((string) $data[$field]) === '') {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        if (array_key_exists('tipo_familiar', $data)) {
            $tipo = strtoupper(trim((string) $data['tipo_familiar']));
            if ($tipo === '') {
                throw new InvalidArgumentException('tipo_familiar no puede estar vacio.');
            }
            $this->validateTipoFamiliar($tipo);
        }

        foreach (['primer_nombre', 'primer_apellido'] as $field) {
            if (!array_key_exists($field, $data)) {
                continue;
            }

            $value = trim((string) $data[$field]);
            if ($value === '') {
                throw new InvalidArgumentException($field . ' no puede estar vacio.');
            }
            if (mb_strlen($value) > 100) {
                throw new InvalidArgumentException($field . ' no puede exceder 100 caracteres.');
            }
        }

        if (array_key_exists('cedula', $data) && $data['cedula'] !== null) {
            $cedula = trim((string) $data['cedula']);
            if ($cedula !== '' && mb_strlen($cedula) > 20) {
                throw new InvalidArgumentException('cedula no puede exceder 20 caracteres.');
            }
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;

        if (array_key_exists('tipo_familiar', $payload)) {
            $payload['tipo_familiar'] = strtoupper(trim((string) $payload['tipo_familiar']));
        }

        foreach (['primer_nombre', 'primer_apellido', 'cedula'] as $field) {
            if (!array_key_exists($field, $payload) || $payload[$field] === null) {
                continue;
            }

            $value = trim((string) $payload[$field]);
            $payload[$field] = $value === '' && $field === 'cedula' ? null : $value;
        }

        return $payload;
    }

    private function validateTipoFamiliar(string $tipo): void
    {
        $permitidos = [self::TIPO_MADRE, self::TIPO_PADRE, self::TIPO_TUTOR];
        if (!in_array($tipo, $permitidos, true)) {
            throw new InvalidArgumentException('tipo_familiar no es valido para familiares.');
        }
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
