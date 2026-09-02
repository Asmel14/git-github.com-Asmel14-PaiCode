<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class EstudianteVacunasModel extends BaseModel
{
    protected string $table = 'estudiante_vacunas';

    protected array $primaryKey = ['estudiante_id', 'vacuna_id'];

    protected array $fillable = [
        'estudiante_id',
        'vacuna_id',
    ];

    public function getByEstudianteId(int $estudianteId): array
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');

        $sql = 'SELECT * FROM `estudiante_vacunas`
                WHERE `estudiante_id` = :estudiante_id
                ORDER BY `vacuna_id` ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estudiante_id', $estudianteId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByVacunaId(int $vacunaId): array
    {
        $this->validatePositiveId($vacunaId, 'vacuna_id');

        $sql = 'SELECT * FROM `estudiante_vacunas`
                WHERE `vacuna_id` = :vacuna_id
                ORDER BY `estudiante_id` ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':vacuna_id', $vacunaId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getVacunaIdsByEstudianteId(int $estudianteId): array
    {
        $rows = $this->getByEstudianteId($estudianteId);
        return array_map(static fn(array $row): int => (int) $row['vacuna_id'], $rows);
    }

    public function existsRelacion(int $estudianteId, int $vacunaId): bool
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');
        $this->validatePositiveId($vacunaId, 'vacuna_id');

        $row = $this->find([
            'estudiante_id' => $estudianteId,
            'vacuna_id' => $vacunaId,
        ]);

        return $row !== null;
    }

    public function asignarVacuna(int $estudianteId, int $vacunaId): bool
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');
        $this->validatePositiveId($vacunaId, 'vacuna_id');

        if ($this->existsRelacion($estudianteId, $vacunaId)) {
            return false;
        }

        $sql = 'INSERT INTO `estudiante_vacunas` (`estudiante_id`, `vacuna_id`)
                VALUES (:estudiante_id, :vacuna_id)';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estudiante_id', $estudianteId, PDO::PARAM_INT);
        $stmt->bindValue(':vacuna_id', $vacunaId, PDO::PARAM_INT);
        $stmt->execute();

        return true;
    }

    public function quitarVacuna(int $estudianteId, int $vacunaId): int
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');
        $this->validatePositiveId($vacunaId, 'vacuna_id');

        return $this->delete([
            'estudiante_id' => $estudianteId,
            'vacuna_id' => $vacunaId,
        ]);
    }

    public function limpiarVacunas(int $estudianteId): int
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');
        return $this->delete(['estudiante_id' => $estudianteId]);
    }

    public function syncVacunas(int $estudianteId, array $vacunaIds): void
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');

        $normalized = $this->normalizeIds($vacunaIds);
        $current = $this->getVacunaIdsByEstudianteId($estudianteId);

        $toAdd = array_values(array_diff($normalized, $current));
        $toRemove = array_values(array_diff($current, $normalized));

        foreach ($toAdd as $vacunaId) {
            $this->asignarVacuna($estudianteId, $vacunaId);
        }

        foreach ($toRemove as $vacunaId) {
            $this->quitarVacuna($estudianteId, $vacunaId);
        }
    }

    private function normalizeIds(array $ids): array
    {
        $result = [];

        foreach ($ids as $id) {
            $intId = (int) $id;
            if ($intId <= 0) {
                throw new InvalidArgumentException('Todos los IDs de vacuna deben ser mayores que cero.');
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
