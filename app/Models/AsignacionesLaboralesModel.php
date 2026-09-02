<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class AsignacionesLaboralesModel extends BaseModel
{
    protected string $table = 'asignaciones_laborales';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'personal_id',
        'anio_escolar_id',
        'departamento_id',
        'cargo_id',
        'condicion_laboral_id',
        'estado',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByPersonal(int $personalId): array
    {
        $sql = 'SELECT * FROM `asignaciones_laborales`
                WHERE `personal_id` = :personal_id
                ORDER BY `anio_escolar_id` DESC, `id` DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':personal_id', $personalId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByAnioEscolar(int $anioEscolarId): array
    {
        return $this->where(['anio_escolar_id' => $anioEscolarId], 1000);
    }

    public function getActivasByAnioEscolar(int $anioEscolarId): array
    {
        $sql = 'SELECT * FROM `asignaciones_laborales`
                WHERE `anio_escolar_id` = :anio_escolar_id AND `estado` = 1
                ORDER BY `id` DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':anio_escolar_id', $anioEscolarId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createAsignacion(array $data): int
    {
        $this->validateData($data);

        $exists = $this->find([
            'personal_id' => (int) $data['personal_id'],
            'anio_escolar_id' => (int) $data['anio_escolar_id'],
        ]);

        if ($exists !== null) {
            throw new InvalidArgumentException('Ya existe una asignacion para este personal en el anio escolar indicado.');
        }

        $newId = $this->create($data);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear la asignacion laboral.');
        }

        return $newId;
    }

    public function updateAsignacion(int $id, array $data): int
    {
        $this->validateData($data, true);
        return $this->update(['id' => $id], $data);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = [
            'personal_id',
            'anio_escolar_id',
            'departamento_id',
            'cargo_id',
            'condicion_laboral_id',
        ];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        foreach ($required as $field) {
            if (array_key_exists($field, $data) && (int) $data[$field] <= 0) {
                throw new InvalidArgumentException('El campo ' . $field . ' debe ser mayor que cero.');
            }
        }

        if (array_key_exists('estado', $data)) {
            $estado = (int) $data['estado'];
            if (!in_array($estado, [0, 1], true)) {
                throw new InvalidArgumentException('El campo estado solo permite 0 o 1.');
            }
        }
    }
}
