<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class AuditoriaModel extends BaseModel
{
    protected string $table = 'auditoria';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'usuario_id',
        'modulo',
        'accion',
        'tabla',
        'registro_id',
        'descripcion',
        'ip',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getRecent(int $limit = 100): array
    {
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `auditoria` ORDER BY `id` DESC LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByModulo(string $modulo, int $limit = 200): array
    {
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `auditoria`
                WHERE `modulo` = :modulo
                ORDER BY `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':modulo', trim($modulo));
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByUsuario(int $usuarioId, int $limit = 200): array
    {
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `auditoria`
                WHERE `usuario_id` = :usuario_id
                ORDER BY `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByTabla(string $tabla, int $limit = 200): array
    {
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `auditoria`
                WHERE `tabla` = :tabla
                ORDER BY `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':tabla', trim($tabla));
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function logEvent(array $data): int
    {
        $this->validateData($data);

        $newId = $this->create($data);
        if ($newId === null) {
            throw new RuntimeException('No se pudo registrar el evento de auditoria.');
        }

        return $newId;
    }

    private function validateData(array $data): void
    {
        $required = ['modulo', 'accion'];
        foreach ($required as $field) {
            if (!array_key_exists($field, $data) || trim((string) $data[$field]) === '') {
                throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
            }
        }

        if (array_key_exists('usuario_id', $data) && $data['usuario_id'] !== null && (int) $data['usuario_id'] <= 0) {
            throw new InvalidArgumentException('usuario_id debe ser mayor que cero o null.');
        }

        if (array_key_exists('registro_id', $data) && $data['registro_id'] !== null && (int) $data['registro_id'] <= 0) {
            throw new InvalidArgumentException('registro_id debe ser mayor que cero o null.');
        }

        if (array_key_exists('ip', $data) && $data['ip'] !== null) {
            $ip = trim((string) $data['ip']);
            if ($ip !== '' && strlen($ip) > 45) {
                throw new InvalidArgumentException('ip no puede exceder 45 caracteres.');
            }
        }
    }

    private function normalizeLimit(int $limit): int
    {
        if ($limit < 1) {
            return 1;
        }

        if ($limit > 1000) {
            return 1000;
        }

        return $limit;
    }
}
