<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class EstudianteDiscapacidadesModel extends BaseModel
{
    protected string $table = 'estudiante_discapacidades';

    protected array $primaryKey = ['estudiante_id', 'discapacidad_id'];

    protected array $fillable = [
        'estudiante_id',
        'discapacidad_id',
    ];

    public function getByEstudianteId(int $estudianteId): array
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');

        $sql = 'SELECT * FROM `estudiante_discapacidades`
                WHERE `estudiante_id` = :estudiante_id
                ORDER BY `discapacidad_id` ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estudiante_id', $estudianteId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getDiscapacidadIdsByEstudianteId(int $estudianteId): array
    {
        $rows = $this->getByEstudianteId($estudianteId);
        return array_map(static fn(array $row): int => (int) $row['discapacidad_id'], $rows);
    }

    public function existsRelacion(int $estudianteId, int $discapacidadId): bool
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');
        $this->validatePositiveId($discapacidadId, 'discapacidad_id');

        $row = $this->find([
            'estudiante_id' => $estudianteId,
            'discapacidad_id' => $discapacidadId,
        ]);

        return $row !== null;
    }

    public function asignarDiscapacidad(int $estudianteId, int $discapacidadId): bool
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');
        $this->validatePositiveId($discapacidadId, 'discapacidad_id');

        if ($this->existsRelacion($estudianteId, $discapacidadId)) {
            return false;
        }

        $sql = 'INSERT INTO `estudiante_discapacidades` (`estudiante_id`, `discapacidad_id`)
                VALUES (:estudiante_id, :discapacidad_id)';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estudiante_id', $estudianteId, PDO::PARAM_INT);
        $stmt->bindValue(':discapacidad_id', $discapacidadId, PDO::PARAM_INT);
        $stmt->execute();

        return true;
    }

    public function quitarDiscapacidad(int $estudianteId, int $discapacidadId): int
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');
        $this->validatePositiveId($discapacidadId, 'discapacidad_id');

        return $this->delete([
            'estudiante_id' => $estudianteId,
            'discapacidad_id' => $discapacidadId,
        ]);
    }

    public function limpiarDiscapacidades(int $estudianteId): int
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');
        return $this->delete(['estudiante_id' => $estudianteId]);
    }

    public function syncDiscapacidades(int $estudianteId, array $discapacidadIds): void
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');

        $normalized = $this->normalizeIds($discapacidadIds);
        $current = $this->getDiscapacidadIdsByEstudianteId($estudianteId);

        $toAdd = array_values(array_diff($normalized, $current));
        $toRemove = array_values(array_diff($current, $normalized));

        foreach ($toAdd as $discapacidadId) {
            $this->asignarDiscapacidad($estudianteId, $discapacidadId);
        }

        foreach ($toRemove as $discapacidadId) {
            $this->quitarDiscapacidad($estudianteId, $discapacidadId);
        }
    }

    private function normalizeIds(array $ids): array
    {
        $result = [];

        foreach ($ids as $id) {
            $intId = (int) $id;
            if ($intId <= 0) {
                throw new InvalidArgumentException('Todos los IDs de discapacidad deben ser mayores que cero.');
            }

            $result[] = $intId;
        }

        $result = array_values(array_unique($result));
        sort($result);

        return $result;
    }

    private function validatePositiveId(int $id, string $field): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException($field . ' debe ser mayor que cero.');
        }
    }
}
