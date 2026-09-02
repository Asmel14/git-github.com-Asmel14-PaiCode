<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class EstudiantesModel extends BaseModel
{
    protected string $table = 'estudiantes';

    protected array $primaryKey = ['id'];

    private const SEXO_MASCULINO = 'MASCULINO';
    private const SEXO_FEMENINO = 'FEMENINO';

    private const ESTADO_CIVIL_SOLTERO = 'SOLTERO';
    private const ESTADO_CIVIL_CASADO = 'CASADO';
    private const ESTADO_CIVIL_VIUDO = 'VIUDO';
    private const ESTADO_CIVIL_DIVORCIADO = 'DIVORCIADO';

    protected array $fillable = [
        'foto',
        'id_sigerd',
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
        'observaciones',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getBySigerd(string $idSigerd): ?array
    {
        return $this->find(['id_sigerd' => trim($idSigerd)]);
    }

    public function getAllOrdered(int $limit = 500): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `estudiantes`
                ORDER BY `primer_apellido` ASC, `primer_nombre` ASC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function searchByNombre(string $term, int $limit = 200): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $search = '%' . trim($term) . '%';

        $sql = 'SELECT * FROM `estudiantes`
                     WHERE `primer_nombre` LIKE :term1
                         OR `segundo_nombre` LIKE :term2
                         OR `primer_apellido` LIKE :term3
                         OR `segundo_apellido` LIKE :term4
                ORDER BY `primer_apellido` ASC, `primer_nombre` ASC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
          $stmt->bindValue(':term1', $search);
          $stmt->bindValue(':term2', $search);
          $stmt->bindValue(':term3', $search);
          $stmt->bindValue(':term4', $search);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createEstudiante(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        if (isset($payload['id_sigerd']) && $payload['id_sigerd'] !== null) {
            $existing = $this->getBySigerd((string) $payload['id_sigerd']);
            if ($existing !== null) {
                throw new InvalidArgumentException('Ya existe un estudiante con ese id_sigerd.');
            }
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el estudiante.');
        }

        return $newId;
    }

    public function updateEstudiante(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El estudiante indicado no existe.');
        }

        $payload = $this->normalizePayload($data);

        if (array_key_exists('id_sigerd', $payload) && $payload['id_sigerd'] !== null) {
            $existing = $this->getBySigerd((string) $payload['id_sigerd']);
            if ($existing !== null && (int) $existing['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe un estudiante con ese id_sigerd.');
            }
        }

        return $this->update(['id' => $id], $payload);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['primer_nombre', 'primer_apellido'];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data) || trim((string) $data[$field]) === '') {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        $textRules = [
            'foto' => 255,
            'id_sigerd' => 50,
            'primer_nombre' => 100,
            'segundo_nombre' => 100,
            'primer_apellido' => 100,
            'segundo_apellido' => 100,
            'nacionalidad' => 100,
            'telefono' => 30,
            'celular' => 30,
            'whatsapp' => 30,
        ];

        foreach ($textRules as $field => $maxLength) {
            if (!array_key_exists($field, $data) || $data[$field] === null) {
                continue;
            }

            $value = trim((string) $data[$field]);
            if (in_array($field, $required, true) && $value === '') {
                throw new InvalidArgumentException($field . ' no puede estar vacio.');
            }

            if ($value !== '' && mb_strlen($value) > $maxLength) {
                throw new InvalidArgumentException($field . ' no puede exceder ' . $maxLength . ' caracteres.');
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
            if ($sexo !== '') {
                $this->validateSexo($sexo);
            }
        }

        if (array_key_exists('estado_civil', $data) && $data['estado_civil'] !== null) {
            $estadoCivil = strtoupper(trim((string) $data['estado_civil']));
            if ($estadoCivil !== '') {
                $this->validateEstadoCivil($estadoCivil);
            }
        }

        if (array_key_exists('observaciones', $data) && $data['observaciones'] !== null) {
            if (trim((string) $data['observaciones']) === '') {
                $data['observaciones'] = null;
            }
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;

        $textFields = [
            'foto',
            'id_sigerd',
            'primer_nombre',
            'segundo_nombre',
            'primer_apellido',
            'segundo_apellido',
            'nacionalidad',
            'telefono',
            'celular',
            'whatsapp',
            'observaciones',
        ];

        foreach ($textFields as $field) {
            if (!array_key_exists($field, $payload) || $payload[$field] === null) {
                continue;
            }

            $value = trim((string) $payload[$field]);
            $payload[$field] = $value === '' && !in_array($field, ['primer_nombre', 'primer_apellido'], true)
                ? null
                : $value;
        }

        if (array_key_exists('sexo', $payload) && $payload['sexo'] !== null) {
            $sexo = strtoupper(trim((string) $payload['sexo']));
            $payload['sexo'] = $sexo === '' ? null : $sexo;
        }

        if (array_key_exists('estado_civil', $payload) && $payload['estado_civil'] !== null) {
            $estadoCivil = strtoupper(trim((string) $payload['estado_civil']));
            $payload['estado_civil'] = $estadoCivil === '' ? null : $estadoCivil;
        }

        if (array_key_exists('fecha_nacimiento', $payload) && $payload['fecha_nacimiento'] !== null) {
            $fecha = trim((string) $payload['fecha_nacimiento']);
            $payload['fecha_nacimiento'] = $fecha === '' ? null : $fecha;
        }

        return $payload;
    }

    private function validateSexo(string $sexo): void
    {
        $permitidos = [self::SEXO_MASCULINO, self::SEXO_FEMENINO];
        if (!in_array($sexo, $permitidos, true)) {
            throw new InvalidArgumentException('sexo no es valido para estudiantes.');
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
            throw new InvalidArgumentException('estado_civil no es valido para estudiantes.');
        }
    }

    private function isValidDate(string $date): bool
    {
        $dt = DateTime::createFromFormat('Y-m-d', $date);
        return $dt !== false && $dt->format('Y-m-d') === $date;
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
