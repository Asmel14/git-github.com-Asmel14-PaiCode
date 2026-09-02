<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class EstudianteFamiliaresModel extends BaseModel
{
    protected string $table = 'estudiante_familiares';

    protected array $primaryKey = ['estudiante_id', 'familiar_id'];

    protected array $fillable = [
        'estudiante_id',
        'familiar_id',
    ];

    public function getByEstudianteId(int $estudianteId): array
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');

        $sql = 'SELECT * FROM `estudiante_familiares`
                WHERE `estudiante_id` = :estudiante_id
                ORDER BY `familiar_id` ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estudiante_id', $estudianteId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByFamiliarId(int $familiarId): array
    {
        $this->validatePositiveId($familiarId, 'familiar_id');

        $sql = 'SELECT * FROM `estudiante_familiares`
                WHERE `familiar_id` = :familiar_id
                ORDER BY `estudiante_id` ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':familiar_id', $familiarId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getFamiliarIdsByEstudianteId(int $estudianteId): array
    {
        $rows = $this->getByEstudianteId($estudianteId);
        return array_map(static fn(array $row): int => (int) $row['familiar_id'], $rows);
    }

    public function existsRelacion(int $estudianteId, int $familiarId): bool
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');
        $this->validatePositiveId($familiarId, 'familiar_id');

        $row = $this->find([
            'estudiante_id' => $estudianteId,
            'familiar_id' => $familiarId,
        ]);

        return $row !== null;
    }

    public function asignarFamiliar(int $estudianteId, int $familiarId): bool
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');
        $this->validatePositiveId($familiarId, 'familiar_id');

        if ($this->existsRelacion($estudianteId, $familiarId)) {
            return false;
        }

        $sql = 'INSERT INTO `estudiante_familiares` (`estudiante_id`, `familiar_id`)
                VALUES (:estudiante_id, :familiar_id)';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estudiante_id', $estudianteId, PDO::PARAM_INT);
        $stmt->bindValue(':familiar_id', $familiarId, PDO::PARAM_INT);
        $stmt->execute();

        return true;
    }

    public function quitarFamiliar(int $estudianteId, int $familiarId): int
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');
        $this->validatePositiveId($familiarId, 'familiar_id');

        return $this->delete([
            'estudiante_id' => $estudianteId,
            'familiar_id' => $familiarId,
        ]);
    }

    public function limpiarFamiliares(int $estudianteId): int
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');
        return $this->delete(['estudiante_id' => $estudianteId]);
    }

    public function syncFamiliares(int $estudianteId, array $familiarIds): void
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');

        $normalized = $this->normalizeIds($familiarIds);
        $current = $this->getFamiliarIdsByEstudianteId($estudianteId);

        $toAdd = array_values(array_diff($normalized, $current));
        $toRemove = array_values(array_diff($current, $normalized));

        foreach ($toAdd as $familiarId) {
            $this->asignarFamiliar($estudianteId, $familiarId);
        }

        foreach ($toRemove as $familiarId) {
            $this->quitarFamiliar($estudianteId, $familiarId);
        }
    }

    private function normalizeIds(array $ids): array
    {
        $result = [];

        foreach ($ids as $id) {
            $intId = (int) $id;
            if ($intId <= 0) {
                throw new InvalidArgumentException('Todos los IDs de familiar deben ser mayores que cero.');
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
