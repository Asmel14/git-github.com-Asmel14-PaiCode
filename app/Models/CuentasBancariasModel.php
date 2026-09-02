<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class CuentasBancariasModel extends BaseModel
{
    protected string $table = 'cuentas_bancarias';

    protected array $primaryKey = ['id'];

    private const TIPO_AHORRO = 'AHORRO';
    private const TIPO_CORRIENTE = 'CORRIENTE';

    protected array $fillable = [
        'banco',
        'nombre_cuenta',
        'tipo_cuenta',
        'numero_cuenta',
        'titular',
        'moneda',
        'saldo_inicial',
        'estado',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByNumeroCuenta(string $numeroCuenta): ?array
    {
        return $this->find(['numero_cuenta' => trim($numeroCuenta)]);
    }

    public function getActivas(): array
    {
        $sql = 'SELECT * FROM `cuentas_bancarias` WHERE `estado` = 1 ORDER BY `banco` ASC, `nombre_cuenta` ASC';
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    public function getByBanco(string $banco, bool $soloActivas = true): array
    {
        $sql = 'SELECT * FROM `cuentas_bancarias` WHERE `banco` = :banco';
        if ($soloActivas) {
            $sql .= ' AND `estado` = 1';
        }
        $sql .= ' ORDER BY `nombre_cuenta` ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':banco', trim($banco));
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByTipo(string $tipo, bool $soloActivas = true): array
    {
        $tipoNormalizado = strtoupper(trim($tipo));
        $this->validateTipoCuenta($tipoNormalizado);

        $sql = 'SELECT * FROM `cuentas_bancarias` WHERE `tipo_cuenta` = :tipo_cuenta';
        if ($soloActivas) {
            $sql .= ' AND `estado` = 1';
        }
        $sql .= ' ORDER BY `banco` ASC, `nombre_cuenta` ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':tipo_cuenta', $tipoNormalizado);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createCuenta(array $data): int
    {
        $this->validateData($data);

        $payload = $this->normalizePayload($data);

        if ($this->getByNumeroCuenta((string) $payload['numero_cuenta']) !== null) {
            throw new InvalidArgumentException('Ya existe una cuenta con ese numero.');
        }

        if (!array_key_exists('estado', $payload)) {
            $payload['estado'] = 1;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear la cuenta bancaria.');
        }

        return $newId;
    }

    public function updateCuenta(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('La cuenta bancaria indicada no existe.');
        }

        $payload = $this->normalizePayload($data);

        if (array_key_exists('numero_cuenta', $payload)) {
            $existingByNumber = $this->getByNumeroCuenta((string) $payload['numero_cuenta']);
            if ($existingByNumber !== null && (int) $existingByNumber['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe una cuenta con ese numero.');
            }
        }

        return $this->update(['id' => $id], $payload);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['banco', 'nombre_cuenta', 'tipo_cuenta', 'numero_cuenta'];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data) || trim((string) $data[$field]) === '') {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        foreach (['banco', 'nombre_cuenta'] as $field) {
            if (array_key_exists($field, $data)) {
                $value = trim((string) $data[$field]);
                if ($value === '') {
                    throw new InvalidArgumentException($field . ' no puede estar vacio.');
                }
                if (mb_strlen($value) > 150) {
                    throw new InvalidArgumentException($field . ' no puede exceder 150 caracteres.');
                }
            }
        }

        if (array_key_exists('tipo_cuenta', $data)) {
            $this->validateTipoCuenta(strtoupper(trim((string) $data['tipo_cuenta'])));
        }

        if (array_key_exists('numero_cuenta', $data)) {
            $numero = trim((string) $data['numero_cuenta']);
            if ($numero === '') {
                throw new InvalidArgumentException('numero_cuenta no puede estar vacio.');
            }
            if (mb_strlen($numero) > 100) {
                throw new InvalidArgumentException('numero_cuenta no puede exceder 100 caracteres.');
            }
        }

        if (array_key_exists('titular', $data) && $data['titular'] !== null) {
            $titular = trim((string) $data['titular']);
            if ($titular !== '' && mb_strlen($titular) > 255) {
                throw new InvalidArgumentException('titular no puede exceder 255 caracteres.');
            }
        }

        if (array_key_exists('moneda', $data)) {
            $moneda = strtoupper(trim((string) $data['moneda']));
            if ($moneda === '') {
                throw new InvalidArgumentException('moneda no puede estar vacia.');
            }
            if (mb_strlen($moneda) > 10) {
                throw new InvalidArgumentException('moneda no puede exceder 10 caracteres.');
            }
        }

        if (array_key_exists('saldo_inicial', $data)) {
            $saldo = round((float) $data['saldo_inicial'], 2);
            if ($saldo < 0) {
                throw new InvalidArgumentException('saldo_inicial no puede ser negativo.');
            }
        }

        if (array_key_exists('estado', $data)) {
            $estado = (int) $data['estado'];
            if (!in_array($estado, [0, 1], true)) {
                throw new InvalidArgumentException('estado solo permite 0 o 1.');
            }
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;

        foreach (['banco', 'nombre_cuenta', 'numero_cuenta'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = trim((string) $payload[$field]);
            }
        }

        if (array_key_exists('tipo_cuenta', $payload)) {
            $payload['tipo_cuenta'] = strtoupper(trim((string) $payload['tipo_cuenta']));
        }

        if (array_key_exists('titular', $payload) && $payload['titular'] !== null) {
            $payload['titular'] = trim((string) $payload['titular']);
            if ($payload['titular'] === '') {
                $payload['titular'] = null;
            }
        }

        if (array_key_exists('moneda', $payload)) {
            $payload['moneda'] = strtoupper(trim((string) $payload['moneda']));
        }

        if (array_key_exists('saldo_inicial', $payload)) {
            $payload['saldo_inicial'] = round((float) $payload['saldo_inicial'], 2);
        }

        if (array_key_exists('estado', $payload)) {
            $payload['estado'] = (int) $payload['estado'];
        }

        return $payload;
    }

    private function validateTipoCuenta(string $tipo): void
    {
        $permitidos = [self::TIPO_AHORRO, self::TIPO_CORRIENTE];
        if (!in_array($tipo, $permitidos, true)) {
            throw new InvalidArgumentException('tipo_cuenta no es valido para cuentas_bancarias.');
        }
    }
}
