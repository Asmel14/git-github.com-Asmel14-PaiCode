<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class InscripcionRequisitosModel extends BaseModel
{
    protected string $table = 'inscripcion_requisitos';

    protected array $primaryKey = ['inscripcion_id', 'requisito_id'];

    protected array $fillable = [
        'inscripcion_id',
        'requisito_id',
        'presentado',
    ];

    public function getByInscripcionId(int $inscripcionId): array
    {
        $this->validatePositiveId($inscripcionId, 'inscripcion_id');

        $sql = 'SELECT * FROM `inscripcion_requisitos`
                WHERE `inscripcion_id` = :inscripcion_id
                ORDER BY `requisito_id` ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':inscripcion_id', $inscripcionId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getPendientesByInscripcionId(int $inscripcionId): array
    {
        $this->validatePositiveId($inscripcionId, 'inscripcion_id');

        $sql = 'SELECT * FROM `inscripcion_requisitos`
                WHERE `inscripcion_id` = :inscripcion_id
                  AND `presentado` = 0
                ORDER BY `requisito_id` ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':inscripcion_id', $inscripcionId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function existsRelacion(int $inscripcionId, int $requisitoId): bool
    {
        $this->validatePositiveId($inscripcionId, 'inscripcion_id');
        $this->validatePositiveId($requisitoId, 'requisito_id');

        $row = $this->find([
            'inscripcion_id' => $inscripcionId,
            'requisito_id' => $requisitoId,
        ]);

        return $row !== null;
    }

    public function asignarRequisito(int $inscripcionId, int $requisitoId, int $presentado = 0): bool
    {
        $this->validatePositiveId($inscripcionId, 'inscripcion_id');
        $this->validatePositiveId($requisitoId, 'requisito_id');
        $this->validatePresentado($presentado);

        if ($this->existsRelacion($inscripcionId, $requisitoId)) {
            return false;
        }

        $sql = 'INSERT INTO `inscripcion_requisitos` (`inscripcion_id`, `requisito_id`, `presentado`)
                VALUES (:inscripcion_id, :requisito_id, :presentado)';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':inscripcion_id', $inscripcionId, PDO::PARAM_INT);
        $stmt->bindValue(':requisito_id', $requisitoId, PDO::PARAM_INT);
        $stmt->bindValue(':presentado', $presentado, PDO::PARAM_INT);
        $stmt->execute();

        return true;
    }

    public function marcarPresentado(int $inscripcionId, int $requisitoId): int
    {
        return $this->updatePresentado($inscripcionId, $requisitoId, 1);
    }

    public function marcarPendiente(int $inscripcionId, int $requisitoId): int
    {
        return $this->updatePresentado($inscripcionId, $requisitoId, 0);
    }

    public function quitarRequisito(int $inscripcionId, int $requisitoId): int
    {
        $this->validatePositiveId($inscripcionId, 'inscripcion_id');
        $this->validatePositiveId($requisitoId, 'requisito_id');

        return $this->delete([
            'inscripcion_id' => $inscripcionId,
            'requisito_id' => $requisitoId,
        ]);
    }

    public function limpiarPorInscripcionId(int $inscripcionId): int
    {
        $this->validatePositiveId($inscripcionId, 'inscripcion_id');
        return $this->delete(['inscripcion_id' => $inscripcionId]);
    }

    public function syncRequisitos(int $inscripcionId, array $requisitosPresentados): void
    {
        $this->validatePositiveId($inscripcionId, 'inscripcion_id');

        $ids = $this->normalizeIds($requisitosPresentados);
        $currentRows = $this->getByInscripcionId($inscripcionId);
        $currentMap = [];

        foreach ($currentRows as $row) {
            $currentMap[(int) $row['requisito_id']] = (int) $row['presentado'];
        }

        foreach ($ids as $requisitoId) {
            if (!array_key_exists($requisitoId, $currentMap)) {
                $this->asignarRequisito($inscripcionId, $requisitoId, 1);
                continue;
            }

            if ($currentMap[$requisitoId] !== 1) {
                $this->marcarPresentado($inscripcionId, $requisitoId);
            }
        }

        foreach (array_keys($currentMap) as $requisitoId) {
            if (!in_array($requisitoId, $ids, true) && $currentMap[$requisitoId] !== 0) {
                $this->marcarPendiente($inscripcionId, $requisitoId);
            }
        }
    }

    private function updatePresentado(int $inscripcionId, int $requisitoId, int $presentado): int
    {
        $this->validatePositiveId($inscripcionId, 'inscripcion_id');
        $this->validatePositiveId($requisitoId, 'requisito_id');
        $this->validatePresentado($presentado);

        if (!$this->existsRelacion($inscripcionId, $requisitoId)) {
            throw new InvalidArgumentException('La relacion inscripcion-requisito no existe.');
        }

        return $this->update(
            [
                'inscripcion_id' => $inscripcionId,
                'requisito_id' => $requisitoId,
            ],
            [
                'presentado' => $presentado,
            ]
        );
    }

    private function normalizeIds(array $ids): array
    {
        $result = [];

        foreach ($ids as $id) {
            $intId = (int) $id;
            if ($intId <= 0) {
                throw new InvalidArgumentException('Todos los IDs de requisito deben ser mayores que cero.');
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

    private function validatePresentado(int $presentado): void
    {
        if (!in_array($presentado, [0, 1], true)) {
            throw new InvalidArgumentException('presentado solo permite 0 o 1.');
        }
    }
}
