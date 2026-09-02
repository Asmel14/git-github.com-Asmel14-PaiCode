<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class ParametrosFinancierosModel extends BaseModel
{
    protected string $table = 'parametros_financieros';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'anio_escolar_id',
        'dia_vencimiento_mensual',
        'mora_mensual',
        'regla_especial',
        'pago_agosto_libera_junio',
        'estado',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByAnioEscolarId(int $anioEscolarId): ?array
    {
        $this->validatePositiveId($anioEscolarId, 'anio_escolar_id');
        return $this->find(['anio_escolar_id' => $anioEscolarId]);
    }

    public function getActivos(int $limit = 100): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `parametros_financieros`
                WHERE `estado` = 1
                ORDER BY `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getAllOrdered(int $limit = 500): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `parametros_financieros`
                ORDER BY `anio_escolar_id` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createParametro(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        $exists = $this->getByAnioEscolarId((int) $payload['anio_escolar_id']);
        if ($exists !== null) {
            throw new InvalidArgumentException('Ya existe un parametro financiero para ese anio_escolar_id.');
        }

        if (!array_key_exists('estado', $payload)) {
            $payload['estado'] = 1;
        }

        if (!array_key_exists('pago_agosto_libera_junio', $payload)) {
            $payload['pago_agosto_libera_junio'] = 1;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el parametro financiero.');
        }

        return $newId;
    }

    public function updateParametro(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El parametro financiero indicado no existe.');
        }

        $payload = $this->normalizePayload($data);

        if (array_key_exists('anio_escolar_id', $payload)) {
            $exists = $this->getByAnioEscolarId((int) $payload['anio_escolar_id']);
            if ($exists !== null && (int) $exists['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe otro parametro financiero para ese anio_escolar_id.');
            }
        }

        return $this->update(['id' => $id], $payload);
    }

    public function activarParametro(int $id): int
    {
        return $this->setEstado($id, 1);
    }

    public function desactivarParametro(int $id): int
    {
        return $this->setEstado($id, 0);
    }

    private function setEstado(int $id, int $estado): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El parametro financiero indicado no existe.');
        }

        if ((int) ($current['estado'] ?? -1) === $estado) {
            return 0;
        }

        return $this->update(['id' => $id], ['estado' => $estado]);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['anio_escolar_id', 'dia_vencimiento_mensual', 'mora_mensual'];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        if (array_key_exists('anio_escolar_id', $data)) {
            $this->validatePositiveId((int) $data['anio_escolar_id'], 'anio_escolar_id');
        }

        if (array_key_exists('dia_vencimiento_mensual', $data)) {
            $dia = (int) $data['dia_vencimiento_mensual'];
            if ($dia < 1 || $dia > 31) {
                throw new InvalidArgumentException('dia_vencimiento_mensual debe estar entre 1 y 31.');
            }
        }

        if (array_key_exists('mora_mensual', $data)) {
            $mora = round((float) $data['mora_mensual'], 2);
            if ($mora < 0) {
                throw new InvalidArgumentException('mora_mensual no puede ser negativa.');
            }
        }

        if (array_key_exists('regla_especial', $data) && $data['regla_especial'] !== null) {
            $regla = trim((string) $data['regla_especial']);
            if ($regla !== '' && mb_strlen($regla) > 65535) {
                throw new InvalidArgumentException('regla_especial excede el tamano permitido.');
            }
        }

        foreach (['pago_agosto_libera_junio', 'estado'] as $field) {
            if (array_key_exists($field, $data)) {
                $value = (int) $data[$field];
                if (!in_array($value, [0, 1], true)) {
                    throw new InvalidArgumentException($field . ' solo permite 0 o 1.');
                }
            }
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;

        if (array_key_exists('anio_escolar_id', $payload)) {
            $payload['anio_escolar_id'] = (int) $payload['anio_escolar_id'];
        }

        if (array_key_exists('dia_vencimiento_mensual', $payload)) {
            $payload['dia_vencimiento_mensual'] = (int) $payload['dia_vencimiento_mensual'];
        }

        if (array_key_exists('mora_mensual', $payload)) {
            $payload['mora_mensual'] = round((float) $payload['mora_mensual'], 2);
        }

        if (array_key_exists('regla_especial', $payload) && $payload['regla_especial'] !== null) {
            $regla = trim((string) $payload['regla_especial']);
            $payload['regla_especial'] = $regla === '' ? null : $regla;
        }

        foreach (['pago_agosto_libera_junio', 'estado'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = (int) $payload[$field];
            }
        }

        return $payload;
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
