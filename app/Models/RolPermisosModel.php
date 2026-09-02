<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class RolPermisosModel extends BaseModel
{
    protected string $table = 'rol_permisos';

    protected array $primaryKey = ['rol_id', 'permiso_id'];

    protected array $fillable = [
        'rol_id',
        'permiso_id',
    ];

    public function getByRolAndPermiso(int $rolId, int $permisoId): ?array
    {
        $this->validatePositiveId($rolId, 'rol_id');
        $this->validatePositiveId($permisoId, 'permiso_id');

        return $this->find([
            'rol_id' => $rolId,
            'permiso_id' => $permisoId,
        ]);
    }

    public function getByRolId(int $rolId, int $limit = 1000): array
    {
        $this->validatePositiveId($rolId, 'rol_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `rol_permisos`
                WHERE `rol_id` = :rol_id
                ORDER BY `created_at` DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':rol_id', $rolId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByPermisoId(int $permisoId, int $limit = 1000): array
    {
        $this->validatePositiveId($permisoId, 'permiso_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `rol_permisos`
                WHERE `permiso_id` = :permiso_id
                ORDER BY `created_at` DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':permiso_id', $permisoId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createRelacion(int $rolId, int $permisoId): bool
    {
        $this->validatePositiveId($rolId, 'rol_id');
        $this->validatePositiveId($permisoId, 'permiso_id');

        if ($this->getByRolAndPermiso($rolId, $permisoId) !== null) {
            throw new InvalidArgumentException('La relacion rol-permiso ya existe.');
        }

        $this->create([
            'rol_id' => $rolId,
            'permiso_id' => $permisoId,
        ]);

        return true;
    }

    public function deleteRelacion(int $rolId, int $permisoId): int
    {
        $this->validatePositiveId($rolId, 'rol_id');
        $this->validatePositiveId($permisoId, 'permiso_id');

        return $this->delete([
            'rol_id' => $rolId,
            'permiso_id' => $permisoId,
        ]);
    }

    public function deleteByRolId(int $rolId): int
    {
        $this->validatePositiveId($rolId, 'rol_id');
        return $this->delete(['rol_id' => $rolId]);
    }

    public function syncPermisosByRol(int $rolId, array $permisosIds): array
    {
        $this->validatePositiveId($rolId, 'rol_id');

        $targetIds = $this->sanitizeUniqueIds($permisosIds, 'permisosIds');
        $currentRows = $this->getByRolId($rolId, 5000);
        $currentIds = array_map(static fn(array $row): int => (int) $row['permiso_id'], $currentRows);

        $toAdd = array_values(array_diff($targetIds, $currentIds));
        $toRemove = array_values(array_diff($currentIds, $targetIds));

        $added = 0;
        foreach ($toAdd as $permisoId) {
            if ($this->createRelacion($rolId, $permisoId)) {
                $added++;
            }
        }

        $removed = 0;
        foreach ($toRemove as $permisoId) {
            $removed += $this->deleteRelacion($rolId, $permisoId);
        }

        return [
            'added' => $added,
            'removed' => $removed,
            'final_count' => count($targetIds),
        ];
    }

    private function sanitizeUniqueIds(array $ids, string $field): array
    {
        $result = [];
        foreach ($ids as $id) {
            $value = (int) $id;
            if ($value <= 0) {
                throw new InvalidArgumentException($field . ' contiene un id invalido.');
            }
            $result[$value] = true;
        }

        return array_keys($result);
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
