<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class HistorialSalariosModel extends BaseModel
{
    protected string $table = 'historial_salarios';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'personal_id',
        'salario',
        'fecha_inicio',
        'fecha_fin',
        'motivo',
        'usuario_id',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByPersonalId(int $personalId, int $limit = 200): array
    {
        $this->validatePositiveId($personalId, 'personal_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `historial_salarios`
                WHERE `personal_id` = :personal_id
                ORDER BY `fecha_inicio` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':personal_id', $personalId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getVigenteByPersonalId(int $personalId): ?array
    {
        $this->validatePositiveId($personalId, 'personal_id');

        $sql = 'SELECT * FROM `historial_salarios`
                WHERE `personal_id` = :personal_id AND `fecha_fin` IS NULL
                ORDER BY `fecha_inicio` DESC, `id` DESC
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':personal_id', $personalId, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function createRegistro(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el registro de historial salarial.');
        }

        return $newId;
    }

    public function registrarCambioSalarial(array $data, bool $cerrarAnterior = true): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        $personalId = (int) $payload['personal_id'];
        $fechaInicioNueva = (string) $payload['fecha_inicio'];

        if ($cerrarAnterior) {
            $vigente = $this->getVigenteByPersonalId($personalId);
            if ($vigente !== null) {
                $fechaCierre = date('Y-m-d', strtotime($fechaInicioNueva . ' -1 day'));
                $this->update(
                    ['id' => (int) $vigente['id']],
                    ['fecha_fin' => $fechaCierre]
                );
            }
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo registrar el cambio salarial.');
        }

        return $newId;
    }

    public function updateRegistro(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El registro de historial salarial no existe.');
        }

        $payload = $this->normalizePayload($data);
        return $this->update(['id' => $id], $payload);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['personal_id', 'salario', 'fecha_inicio'];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        if (array_key_exists('personal_id', $data)) {
            $this->validatePositiveId((int) $data['personal_id'], 'personal_id');
        }

        if (array_key_exists('salario', $data)) {
            $salario = round((float) $data['salario'], 2);
            if ($salario <= 0) {
                throw new InvalidArgumentException('salario debe ser mayor que cero.');
            }
        }

        $fechaInicio = null;
        $fechaFin = null;

        if (array_key_exists('fecha_inicio', $data) && $data['fecha_inicio'] !== null && $data['fecha_inicio'] !== '') {
            $fechaInicio = trim((string) $data['fecha_inicio']);
            if (!$this->isValidDate($fechaInicio)) {
                throw new InvalidArgumentException('fecha_inicio debe tener formato Y-m-d.');
            }
        }

        if (array_key_exists('fecha_fin', $data) && $data['fecha_fin'] !== null && $data['fecha_fin'] !== '') {
            $fechaFin = trim((string) $data['fecha_fin']);
            if (!$this->isValidDate($fechaFin)) {
                throw new InvalidArgumentException('fecha_fin debe tener formato Y-m-d.');
            }
        }

        if ($fechaInicio !== null && $fechaFin !== null && $fechaInicio > $fechaFin) {
            throw new InvalidArgumentException('fecha_inicio no puede ser mayor que fecha_fin.');
        }

        if (array_key_exists('motivo', $data) && $data['motivo'] !== null) {
            $motivo = trim((string) $data['motivo']);
            if ($motivo !== '' && mb_strlen($motivo) > 255) {
                throw new InvalidArgumentException('motivo no puede exceder 255 caracteres.');
            }
        }

        if (array_key_exists('usuario_id', $data) && $data['usuario_id'] !== null) {
            $this->validatePositiveId((int) $data['usuario_id'], 'usuario_id');
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;

        if (array_key_exists('personal_id', $payload)) {
            $payload['personal_id'] = (int) $payload['personal_id'];
        }

        if (array_key_exists('salario', $payload)) {
            $payload['salario'] = round((float) $payload['salario'], 2);
        }

        foreach (['fecha_inicio', 'fecha_fin'] as $field) {
            if (!array_key_exists($field, $payload)) {
                continue;
            }

            if ($payload[$field] === null) {
                continue;
            }

            $value = trim((string) $payload[$field]);
            $payload[$field] = $value === '' ? null : $value;
        }

        if (array_key_exists('motivo', $payload) && $payload['motivo'] !== null) {
            $motivo = trim((string) $payload['motivo']);
            $payload['motivo'] = $motivo === '' ? null : $motivo;
        }

        if (array_key_exists('usuario_id', $payload) && $payload['usuario_id'] !== null) {
            $payload['usuario_id'] = (int) $payload['usuario_id'];
        }

        return $payload;
    }

    private function isValidDate(string $date): bool
    {
        $dt = DateTime::createFromFormat('Y-m-d', $date);
        return $dt !== false && $dt->format('Y-m-d') === $date;
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

        if ($limit > 2000) {
            return 2000;
        }

        return $limit;
    }
}
