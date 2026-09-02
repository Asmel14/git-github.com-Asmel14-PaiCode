<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class UsuarioRolesModel extends BaseModel
{
    protected string $table = 'usuario_roles';

    protected array $primaryKey = ['usuario_id', 'rol_id'];

    protected array $fillable = [
        'usuario_id',
        'rol_id',
    ];

    public function getByUsuarioAndRol(int $usuarioId, int $rolId): ?array
    {
        $this->validatePositiveId($usuarioId, 'usuario_id');
        $this->validatePositiveId($rolId, 'rol_id');

        return $this->find([
            'usuario_id' => $usuarioId,
            'rol_id' => $rolId,
        ]);
    }

    public function getByUsuarioId(int $usuarioId, int $limit = 1000): array
    {
        $this->validatePositiveId($usuarioId, 'usuario_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `usuario_roles`
                WHERE `usuario_id` = :usuario_id
                ORDER BY `created_at` DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByRolId(int $rolId, int $limit = 1000): array
    {
        $this->validatePositiveId($rolId, 'rol_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `usuario_roles`
                WHERE `rol_id` = :rol_id
                ORDER BY `created_at` DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':rol_id', $rolId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createRelacion(int $usuarioId, int $rolId): bool
    {
        $this->validatePositiveId($usuarioId, 'usuario_id');
        $this->validatePositiveId($rolId, 'rol_id');

        if ($this->getByUsuarioAndRol($usuarioId, $rolId) !== null) {
            throw new InvalidArgumentException('La relacion usuario-rol ya existe.');
        }

        $this->create([
            'usuario_id' => $usuarioId,
            'rol_id' => $rolId,
        ]);

        return true;
    }

    public function deleteRelacion(int $usuarioId, int $rolId): int
    {
        $this->validatePositiveId($usuarioId, 'usuario_id');
        $this->validatePositiveId($rolId, 'rol_id');

        return $this->delete([
            'usuario_id' => $usuarioId,
            'rol_id' => $rolId,
        ]);
    }

    public function deleteByUsuarioId(int $usuarioId): int
    {
        $this->validatePositiveId($usuarioId, 'usuario_id');
        return $this->delete(['usuario_id' => $usuarioId]);
    }

    public function syncRolesByUsuario(int $usuarioId, array $rolesIds): array
    {
        $this->validatePositiveId($usuarioId, 'usuario_id');

        $targetIds = $this->sanitizeUniqueIds($rolesIds, 'rolesIds');
        $currentRows = $this->getByUsuarioId($usuarioId, 5000);
        $currentIds = array_map(static fn(array $row): int => (int) $row['rol_id'], $currentRows);

        $toAdd = array_values(array_diff($targetIds, $currentIds));
        $toRemove = array_values(array_diff($currentIds, $targetIds));

        $added = 0;
        foreach ($toAdd as $rolId) {
            if ($this->createRelacion($usuarioId, $rolId)) {
                $added++;
            }
        }

        $removed = 0;
        foreach ($toRemove as $rolId) {
            $removed += $this->deleteRelacion($usuarioId, $rolId);
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
