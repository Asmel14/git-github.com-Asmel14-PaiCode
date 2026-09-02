<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class DatosLaboralesModel extends BaseModel
{
    protected string $table = 'datos_laborales';

    protected array $primaryKey = ['id'];

    private const TANDA_MATUTINA = 'MATUTINA';
    private const TANDA_VESPERTINA = 'VESPERTINA';
    private const TANDA_MATUTINA_VESPERTINA = 'MATUTINA_VESPERTINA';

    protected array $fillable = [
        'personal_id',
        'fecha_ingreso',
        'tanda',
        'salario',
        'banco',
        'numero_cuenta_bancaria',
        'acepta_terminos',
        'empleado_activo',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByPersonalId(int $personalId): ?array
    {
        return $this->find(['personal_id' => $personalId]);
    }

    public function getActivos(): array
    {
        $sql = 'SELECT * FROM `datos_laborales` WHERE `empleado_activo` = 1 ORDER BY `id` DESC';
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    public function getByTanda(string $tanda, bool $soloActivos = true): array
    {
        $tandaNormalizada = strtoupper(trim($tanda));
        $this->validateTanda($tandaNormalizada);

        $sql = 'SELECT * FROM `datos_laborales` WHERE `tanda` = :tanda';
        if ($soloActivos) {
            $sql .= ' AND `empleado_activo` = 1';
        }
        $sql .= ' ORDER BY `id` DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':tanda', $tandaNormalizada);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createDatosLaborales(array $data): int
    {
        $this->validateData($data);

        $personalId = (int) $data['personal_id'];
        if ($this->getByPersonalId($personalId) !== null) {
            throw new InvalidArgumentException('Ya existe un registro laboral para ese personal.');
        }

        $payload = $this->normalizePayload($data);

        if (!array_key_exists('acepta_terminos', $payload)) {
            $payload['acepta_terminos'] = 0;
        }

        if (!array_key_exists('empleado_activo', $payload)) {
            $payload['empleado_activo'] = 1;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el registro de datos laborales.');
        }

        return $newId;
    }

    public function updateByPersonalId(int $personalId, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getByPersonalId($personalId);
        if ($current === null) {
            throw new InvalidArgumentException('No existe registro laboral para el personal indicado.');
        }

        $payload = $this->normalizePayload($data);
        return $this->update(['personal_id' => $personalId], $payload);
    }

    public function upsertByPersonalId(int $personalId, array $data): int
    {
        $exists = $this->getByPersonalId($personalId);
        if ($exists === null) {
            $data['personal_id'] = $personalId;
            return $this->createDatosLaborales($data);
        }

        $this->updateByPersonalId($personalId, $data);
        return (int) $exists['id'];
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        if (!$isPartial) {
            if (!array_key_exists('personal_id', $data) || (int) $data['personal_id'] <= 0) {
                throw new InvalidArgumentException('El campo personal_id es obligatorio y debe ser mayor que cero.');
            }
        }

        if (array_key_exists('personal_id', $data) && (int) $data['personal_id'] <= 0) {
            throw new InvalidArgumentException('personal_id debe ser mayor que cero.');
        }

        if (array_key_exists('fecha_ingreso', $data) && $data['fecha_ingreso'] !== null) {
            $fecha = trim((string) $data['fecha_ingreso']);
            if ($fecha !== '' && !$this->isValidDate($fecha)) {
                throw new InvalidArgumentException('fecha_ingreso debe tener formato Y-m-d.');
            }
        }

        if (array_key_exists('tanda', $data) && $data['tanda'] !== null) {
            $tanda = strtoupper(trim((string) $data['tanda']));
            if ($tanda !== '') {
                $this->validateTanda($tanda);
            }
        }

        if (array_key_exists('salario', $data)) {
            $salario = round((float) $data['salario'], 2);
            if ($salario < 0) {
                throw new InvalidArgumentException('salario no puede ser negativo.');
            }
        }

        if (array_key_exists('banco', $data) && $data['banco'] !== null) {
            $banco = trim((string) $data['banco']);
            if ($banco !== '' && mb_strlen($banco) > 150) {
                throw new InvalidArgumentException('banco no puede exceder 150 caracteres.');
            }
        }

        if (array_key_exists('numero_cuenta_bancaria', $data) && $data['numero_cuenta_bancaria'] !== null) {
            $cuenta = trim((string) $data['numero_cuenta_bancaria']);
            if ($cuenta !== '' && mb_strlen($cuenta) > 100) {
                throw new InvalidArgumentException('numero_cuenta_bancaria no puede exceder 100 caracteres.');
            }
        }

        foreach (['acepta_terminos', 'empleado_activo'] as $field) {
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

        if (array_key_exists('fecha_ingreso', $payload) && $payload['fecha_ingreso'] !== null) {
            $fecha = trim((string) $payload['fecha_ingreso']);
            $payload['fecha_ingreso'] = $fecha === '' ? null : $fecha;
        }

        if (array_key_exists('tanda', $payload) && $payload['tanda'] !== null) {
            $tanda = strtoupper(trim((string) $payload['tanda']));
            $payload['tanda'] = $tanda === '' ? null : $tanda;
        }

        if (array_key_exists('salario', $payload)) {
            $payload['salario'] = round((float) $payload['salario'], 2);
        }

        foreach (['banco', 'numero_cuenta_bancaria'] as $field) {
            if (array_key_exists($field, $payload) && $payload[$field] !== null) {
                $value = trim((string) $payload[$field]);
                $payload[$field] = $value === '' ? null : $value;
            }
        }

        foreach (['acepta_terminos', 'empleado_activo'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = (int) $payload[$field];
            }
        }

        return $payload;
    }

    private function validateTanda(string $tanda): void
    {
        $permitidas = [
            self::TANDA_MATUTINA,
            self::TANDA_VESPERTINA,
            self::TANDA_MATUTINA_VESPERTINA,
        ];

        if (!in_array($tanda, $permitidas, true)) {
            throw new InvalidArgumentException('tanda no es valida para datos_laborales.');
        }
    }

    private function isValidDate(string $date): bool
    {
        $dt = DateTime::createFromFormat('Y-m-d', $date);
        return $dt !== false && $dt->format('Y-m-d') === $date;
    }
}
