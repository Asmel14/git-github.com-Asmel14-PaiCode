<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class PersonalModel extends BaseModel
{
    protected string $table = 'personal';

    protected array $primaryKey = ['id'];

    private const SEXO_MASCULINO = 'MASCULINO';
    private const SEXO_FEMENINO = 'FEMENINO';

    private const ESTADO_CIVIL_SOLTERO = 'SOLTERO';
    private const ESTADO_CIVIL_CASADO = 'CASADO';
    private const ESTADO_CIVIL_VIUDO = 'VIUDO';
    private const ESTADO_CIVIL_DIVORCIADO = 'DIVORCIADO';

    protected array $fillable = [
        'foto',
        'cedula_pasaporte',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'fecha_nacimiento',
        'sexo',
        'estado_civil',
        'nacionalidad',
        'telefono',
        'celular',
        'whatsapp',
        'estado',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByDocumento(string $cedulaPasaporte): ?array
    {
        return $this->find(['cedula_pasaporte' => strtoupper(trim($cedulaPasaporte))]);
    }

    public function getActivos(int $limit = 1000): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `personal`
                WHERE `estado` = 1
                ORDER BY `primer_apellido` ASC, `primer_nombre` ASC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getAllOrdered(int $limit = 2000): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `personal`
                ORDER BY `primer_apellido` ASC, `primer_nombre` ASC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function searchByNombre(string $termino, int $limit = 100): array
    {
        $query = trim($termino);
        if ($query === '') {
            throw new InvalidArgumentException('El termino de busqueda no puede estar vacio.');
        }

        $safeLimit = $this->normalizeLimit($limit);
        $like = '%' . $query . '%';

        $sql = 'SELECT * FROM `personal`
                WHERE `primer_nombre` LIKE :q1
                   OR `segundo_nombre` LIKE :q2
                   OR `primer_apellido` LIKE :q3
                   OR `segundo_apellido` LIKE :q4
                   OR `cedula_pasaporte` LIKE :q5
                ORDER BY `primer_apellido` ASC, `primer_nombre` ASC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':q1', $like);
        $stmt->bindValue(':q2', $like);
        $stmt->bindValue(':q3', $like);
        $stmt->bindValue(':q4', $like);
        $stmt->bindValue(':q5', $like);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createPersonal(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        if ($this->getByDocumento((string) $payload['cedula_pasaporte']) !== null) {
            throw new InvalidArgumentException('Ya existe una persona con ese cedula_pasaporte.');
        }

        if (!array_key_exists('estado', $payload)) {
            $payload['estado'] = 1;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el registro de personal.');
        }

        return $newId;
    }

    public function updatePersonal(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El registro de personal indicado no existe.');
        }

        $payload = $this->normalizePayload($data);

        if (array_key_exists('cedula_pasaporte', $payload)) {
            $exists = $this->getByDocumento((string) $payload['cedula_pasaporte']);
            if ($exists !== null && (int) $exists['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe otra persona con ese cedula_pasaporte.');
            }
        }

        return $this->update(['id' => $id], $payload);
    }

    public function activarPersonal(int $id): int
    {
        return $this->setEstado($id, 1);
    }

    public function desactivarPersonal(int $id): int
    {
        return $this->setEstado($id, 0);
    }

    private function setEstado(int $id, int $estado): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El registro de personal indicado no existe.');
        }

        if ((int) ($current['estado'] ?? -1) === $estado) {
            return 0;
        }

        return $this->update(['id' => $id], ['estado' => $estado]);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['cedula_pasaporte', 'primer_nombre', 'primer_apellido'];
        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        $stringRules = [
            'foto' => 255,
            'cedula_pasaporte' => 30,
            'primer_nombre' => 100,
            'segundo_nombre' => 100,
            'primer_apellido' => 100,
            'segundo_apellido' => 100,
            'nacionalidad' => 100,
            'telefono' => 30,
            'celular' => 30,
            'whatsapp' => 30,
        ];

        foreach ($stringRules as $field => $maxLength) {
            if (array_key_exists($field, $data) && $data[$field] !== null) {
                $value = trim((string) $data[$field]);
                if (in_array($field, ['cedula_pasaporte', 'primer_nombre', 'primer_apellido'], true) && $value === '') {
                    throw new InvalidArgumentException($field . ' no puede estar vacio.');
                }
                if ($value !== '' && mb_strlen($value) > $maxLength) {
                    throw new InvalidArgumentException($field . ' no puede exceder ' . $maxLength . ' caracteres.');
                }
            }
        }

        if (array_key_exists('fecha_nacimiento', $data) && $data['fecha_nacimiento'] !== null) {
            $fecha = trim((string) $data['fecha_nacimiento']);
            if ($fecha !== '' && !$this->isValidDate($fecha)) {
                throw new InvalidArgumentException('fecha_nacimiento debe tener formato Y-m-d.');
            }
        }

        if (array_key_exists('sexo', $data) && $data['sexo'] !== null) {
            $sexo = strtoupper(trim((string) $data['sexo']));
            $this->validateSexo($sexo);
        }

        if (array_key_exists('estado_civil', $data) && $data['estado_civil'] !== null) {
            $estadoCivil = strtoupper(trim((string) $data['estado_civil']));
            $this->validateEstadoCivil($estadoCivil);
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

        $nullableFields = [
            'foto',
            'segundo_nombre',
            'segundo_apellido',
            'fecha_nacimiento',
            'sexo',
            'estado_civil',
            'nacionalidad',
            'telefono',
            'celular',
            'whatsapp',
        ];

        foreach ($nullableFields as $field) {
            if (array_key_exists($field, $payload) && $payload[$field] !== null) {
                $value = trim((string) $payload[$field]);
                $payload[$field] = $value === '' ? null : $value;
            }
        }

        foreach (['primer_nombre', 'primer_apellido'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = trim((string) $payload[$field]);
            }
        }

        if (array_key_exists('cedula_pasaporte', $payload)) {
            $payload['cedula_pasaporte'] = strtoupper(trim((string) $payload['cedula_pasaporte']));
        }

        if (array_key_exists('sexo', $payload) && $payload['sexo'] !== null) {
            $payload['sexo'] = strtoupper((string) $payload['sexo']);
        }

        if (array_key_exists('estado_civil', $payload) && $payload['estado_civil'] !== null) {
            $payload['estado_civil'] = strtoupper((string) $payload['estado_civil']);
        }

        if (array_key_exists('estado', $payload)) {
            $payload['estado'] = (int) $payload['estado'];
        }

        return $payload;
    }

    private function validateSexo(string $sexo): void
    {
        $permitidos = [self::SEXO_MASCULINO, self::SEXO_FEMENINO];
        if (!in_array($sexo, $permitidos, true)) {
            throw new InvalidArgumentException('sexo no es valido para personal.');
        }
    }

    private function validateEstadoCivil(string $estadoCivil): void
    {
        $permitidos = [
            self::ESTADO_CIVIL_SOLTERO,
            self::ESTADO_CIVIL_CASADO,
            self::ESTADO_CIVIL_VIUDO,
            self::ESTADO_CIVIL_DIVORCIADO,
        ];

        if (!in_array($estadoCivil, $permitidos, true)) {
            throw new InvalidArgumentException('estado_civil no es valido para personal.');
        }
    }

    private function isValidDate(string $date): bool
    {
        $dt = DateTime::createFromFormat('Y-m-d', $date);
        return $dt !== false && $dt->format('Y-m-d') === $date;
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
