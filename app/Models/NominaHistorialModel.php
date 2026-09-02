<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class NominaHistorialModel extends BaseModel
{
    protected string $table = 'nomina_historial';

    protected array $primaryKey = ['id'];

    private const ESTADO_BORRADOR = 'BORRADOR';
    private const ESTADO_PROCESADA = 'PROCESADA';
    private const ESTADO_PAGADA = 'PAGADA';
    private const ESTADO_ANULADA = 'ANULADA';

    protected array $fillable = [
        'nomina_id',
        'estado_anterior',
        'estado_nuevo',
        'observaciones',
        'usuario_id',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByNominaId(int $nominaId, int $limit = 1000): array
    {
        $this->validatePositiveId($nominaId, 'nomina_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `nomina_historial`
                WHERE `nomina_id` = :nomina_id
                ORDER BY `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nomina_id', $nominaId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByUsuarioId(int $usuarioId, int $limit = 1000): array
    {
        $this->validatePositiveId($usuarioId, 'usuario_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `nomina_historial`
                WHERE `usuario_id` = :usuario_id
                ORDER BY `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByEstadoNuevo(string $estadoNuevo, int $limit = 1000): array
    {
        $estado = strtoupper(trim($estadoNuevo));
        $this->validateEstadoNomina($estado, 'estado_nuevo');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `nomina_historial`
                WHERE `estado_nuevo` = :estado_nuevo
                ORDER BY `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estado_nuevo', $estado);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function registrarCambioEstado(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        if (array_key_exists('estado_anterior', $payload) && $payload['estado_anterior'] === $payload['estado_nuevo']) {
            throw new InvalidArgumentException('estado_anterior y estado_nuevo no pueden ser iguales.');
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo registrar el historial de nomina.');
        }

        return $newId;
    }

    public function updateRegistro(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El registro de historial indicado no existe.');
        }

        $payload = $this->normalizePayload($data);

        $targetAnterior = array_key_exists('estado_anterior', $payload)
            ? $payload['estado_anterior']
            : ($current['estado_anterior'] ?? null);
        $targetNuevo = array_key_exists('estado_nuevo', $payload)
            ? $payload['estado_nuevo']
            : ($current['estado_nuevo'] ?? null);

        if ($targetAnterior !== null && $targetAnterior === $targetNuevo) {
            throw new InvalidArgumentException('estado_anterior y estado_nuevo no pueden ser iguales.');
        }

        return $this->update(['id' => $id], $payload);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        if (!$isPartial) {
            foreach (['nomina_id', 'estado_nuevo'] as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        if (array_key_exists('nomina_id', $data)) {
            $this->validatePositiveId((int) $data['nomina_id'], 'nomina_id');
        }

        if (array_key_exists('usuario_id', $data) && $data['usuario_id'] !== null) {
            $this->validatePositiveId((int) $data['usuario_id'], 'usuario_id');
        }

        if (array_key_exists('estado_anterior', $data) && $data['estado_anterior'] !== null) {
            $estadoAnterior = strtoupper(trim((string) $data['estado_anterior']));
            if ($estadoAnterior === '') {
                throw new InvalidArgumentException('estado_anterior no puede estar vacio.');
            }
            $this->validateEstadoNomina($estadoAnterior, 'estado_anterior');
        }

        if (array_key_exists('estado_nuevo', $data)) {
            $estadoNuevo = strtoupper(trim((string) $data['estado_nuevo']));
            if ($estadoNuevo === '') {
                throw new InvalidArgumentException('estado_nuevo no puede estar vacio.');
            }
            $this->validateEstadoNomina($estadoNuevo, 'estado_nuevo');
        }

        if (array_key_exists('observaciones', $data) && $data['observaciones'] !== null) {
            $obs = trim((string) $data['observaciones']);
            if ($obs !== '' && mb_strlen($obs) > 255) {
                throw new InvalidArgumentException('observaciones no puede exceder 255 caracteres.');
            }
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;

        foreach (['nomina_id', 'usuario_id'] as $field) {
            if (array_key_exists($field, $payload) && $payload[$field] !== null) {
                $payload[$field] = (int) $payload[$field];
            }
        }

        foreach (['estado_anterior', 'estado_nuevo'] as $field) {
            if (array_key_exists($field, $payload) && $payload[$field] !== null) {
                $value = strtoupper(trim((string) $payload[$field]));
                $payload[$field] = $value === '' ? null : $value;
            }
        }

        if (array_key_exists('observaciones', $payload) && $payload['observaciones'] !== null) {
            $obs = trim((string) $payload['observaciones']);
            $payload['observaciones'] = $obs === '' ? null : $obs;
        }

        return $payload;
    }

    private function validateEstadoNomina(string $estado, string $field): void
    {
        $permitidos = [
            self::ESTADO_BORRADOR,
            self::ESTADO_PROCESADA,
            self::ESTADO_PAGADA,
            self::ESTADO_ANULADA,
        ];

        if (!in_array($estado, $permitidos, true)) {
            throw new InvalidArgumentException($field . ' no es valido para nomina_historial.');
        }
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

        if ($limit > 3000) {
            return 3000;
        }

        return $limit;
    }
}
