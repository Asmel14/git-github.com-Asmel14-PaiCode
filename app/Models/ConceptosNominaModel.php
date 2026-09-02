<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class ConceptosNominaModel extends BaseModel
{
    protected string $table = 'conceptos_nomina';

    protected array $primaryKey = ['id'];

    private const TIPO_INGRESO = 'INGRESO';
    private const TIPO_DEDUCCION = 'DEDUCCION';

    protected array $fillable = [
        'nombre',
        'tipo',
        'descripcion',
        'es_porcentaje',
        'valor_default',
        'estado',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByNombre(string $nombre): ?array
    {
        return $this->find(['nombre' => trim($nombre)]);
    }

    public function getActivos(): array
    {
        $sql = 'SELECT * FROM `conceptos_nomina` WHERE `estado` = 1 ORDER BY `tipo` ASC, `nombre` ASC';
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    public function getByTipo(string $tipo, bool $soloActivos = true): array
    {
        $tipoNormalizado = strtoupper(trim($tipo));
        $this->validateTipo($tipoNormalizado);

        $sql = 'SELECT * FROM `conceptos_nomina` WHERE `tipo` = :tipo';
        if ($soloActivos) {
            $sql .= ' AND `estado` = 1';
        }
        $sql .= ' ORDER BY `nombre` ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':tipo', $tipoNormalizado);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getIngresosActivos(): array
    {
        return $this->getByTipo(self::TIPO_INGRESO, true);
    }

    public function getDeduccionesActivas(): array
    {
        return $this->getByTipo(self::TIPO_DEDUCCION, true);
    }

    public function createConcepto(array $data): int
    {
        $this->validateData($data);

        $payload = $this->normalizePayload($data);

        if ($this->getByNombre((string) $payload['nombre']) !== null) {
            throw new InvalidArgumentException('Ya existe un concepto de nomina con ese nombre.');
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el concepto de nomina.');
        }

        return $newId;
    }

    public function updateConcepto(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El concepto de nomina indicado no existe.');
        }

        $payload = $this->normalizePayload($data);

        if (array_key_exists('nombre', $payload)) {
            $existingByName = $this->getByNombre((string) $payload['nombre']);
            if ($existingByName !== null && (int) $existingByName['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe un concepto de nomina con ese nombre.');
            }
        }

        return $this->update(['id' => $id], $payload);
    }

    public function calcularMonto(float $base, array $concepto): float
    {
        if ($base < 0) {
            throw new InvalidArgumentException('La base no puede ser negativa.');
        }

        $esPorcentaje = (int) ($concepto['es_porcentaje'] ?? 0) === 1;
        $valor = round((float) ($concepto['valor_default'] ?? 0), 2);

        if ($esPorcentaje) {
            return round(($base * $valor) / 100, 2);
        }

        return $valor;
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['nombre', 'tipo'];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data) || trim((string) $data[$field]) === '') {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        if (array_key_exists('nombre', $data)) {
            $nombre = trim((string) $data['nombre']);
            if ($nombre === '') {
                throw new InvalidArgumentException('nombre no puede estar vacio.');
            }
            if (mb_strlen($nombre) > 150) {
                throw new InvalidArgumentException('nombre no puede exceder 150 caracteres.');
            }
        }

        if (array_key_exists('tipo', $data)) {
            $this->validateTipo(strtoupper(trim((string) $data['tipo'])));
        }

        foreach (['es_porcentaje', 'estado'] as $field) {
            if (array_key_exists($field, $data)) {
                $value = (int) $data[$field];
                if (!in_array($value, [0, 1], true)) {
                    throw new InvalidArgumentException($field . ' solo permite 0 o 1.');
                }
            }
        }

        if (array_key_exists('valor_default', $data)) {
            $valor = round((float) $data['valor_default'], 2);
            if ($valor < 0) {
                throw new InvalidArgumentException('valor_default no puede ser negativo.');
            }

            $esPorcentaje = array_key_exists('es_porcentaje', $data)
                ? (int) $data['es_porcentaje']
                : 0;

            if ($esPorcentaje === 1 && $valor > 100) {
                throw new InvalidArgumentException('Si es_porcentaje es 1, valor_default no puede exceder 100.');
            }
        }

        if (array_key_exists('descripcion', $data) && $data['descripcion'] !== null) {
            if (mb_strlen(trim((string) $data['descripcion'])) > 255) {
                throw new InvalidArgumentException('descripcion no puede exceder 255 caracteres.');
            }
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;

        if (array_key_exists('nombre', $payload)) {
            $payload['nombre'] = trim((string) $payload['nombre']);
        }

        if (array_key_exists('tipo', $payload)) {
            $payload['tipo'] = strtoupper(trim((string) $payload['tipo']));
        }

        foreach (['es_porcentaje', 'estado'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = (int) $payload[$field];
            }
        }

        if (array_key_exists('valor_default', $payload)) {
            $payload['valor_default'] = round((float) $payload['valor_default'], 2);
        }

        return $payload;
    }

    private function validateTipo(string $tipo): void
    {
        $permitidos = [self::TIPO_INGRESO, self::TIPO_DEDUCCION];
        if (!in_array($tipo, $permitidos, true)) {
            throw new InvalidArgumentException('tipo no es valido para conceptos_nomina.');
        }
    }
}
