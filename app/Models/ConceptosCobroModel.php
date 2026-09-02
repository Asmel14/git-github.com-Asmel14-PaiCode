<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class ConceptosCobroModel extends BaseModel
{
    protected string $table = 'conceptos_cobro';

    protected array $primaryKey = ['id'];

    private const TIPO_INSCRIPCION = 'INSCRIPCION';
    private const TIPO_COLEGIATURA = 'COLEGIATURA';
    private const TIPO_SERVICIO = 'SERVICIO';
    private const TIPO_MORA = 'MORA';

    protected array $fillable = [
        'codigo',
        'nombre',
        'tipo',
        'requiere_periodo',
        'genera_mora',
        'activo',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByCodigo(string $codigo): ?array
    {
        return $this->find(['codigo' => strtoupper(trim($codigo))]);
    }

    public function getByNombre(string $nombre): ?array
    {
        return $this->find(['nombre' => trim($nombre)]);
    }

    public function getActivos(): array
    {
        $sql = 'SELECT * FROM `conceptos_cobro` WHERE `activo` = 1 ORDER BY `nombre` ASC';
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    public function getByTipo(string $tipo, bool $soloActivos = true): array
    {
        $tipoNormalizado = strtoupper(trim($tipo));
        $this->validateTipo($tipoNormalizado);

        $sql = 'SELECT * FROM `conceptos_cobro` WHERE `tipo` = :tipo';
        if ($soloActivos) {
            $sql .= ' AND `activo` = 1';
        }
        $sql .= ' ORDER BY `nombre` ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':tipo', $tipoNormalizado);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getConceptosQueGeneranMora(): array
    {
        $sql = 'SELECT * FROM `conceptos_cobro`
                WHERE `genera_mora` = 1 AND `activo` = 1
                ORDER BY `nombre` ASC';

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function createConcepto(array $data): int
    {
        $this->validateData($data);

        $payload = $this->normalizePayload($data);

        if ($this->getByCodigo((string) $payload['codigo']) !== null) {
            throw new InvalidArgumentException('Ya existe un concepto con ese codigo.');
        }

        if ($this->getByNombre((string) $payload['nombre']) !== null) {
            throw new InvalidArgumentException('Ya existe un concepto con ese nombre.');
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el concepto de cobro.');
        }

        return $newId;
    }

    public function updateConcepto(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El concepto indicado no existe.');
        }

        $payload = $this->normalizePayload($data);

        if (array_key_exists('codigo', $payload)) {
            $existingByCode = $this->getByCodigo((string) $payload['codigo']);
            if ($existingByCode !== null && (int) $existingByCode['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe un concepto con ese codigo.');
            }
        }

        if (array_key_exists('nombre', $payload)) {
            $existingByName = $this->getByNombre((string) $payload['nombre']);
            if ($existingByName !== null && (int) $existingByName['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe un concepto con ese nombre.');
            }
        }

        return $this->update(['id' => $id], $payload);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['codigo', 'nombre', 'tipo'];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data) || trim((string) $data[$field]) === '') {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        if (array_key_exists('codigo', $data)) {
            $codigo = strtoupper(trim((string) $data['codigo']));
            if ($codigo === '') {
                throw new InvalidArgumentException('codigo no puede estar vacio.');
            }
            if (mb_strlen($codigo) > 30) {
                throw new InvalidArgumentException('codigo no puede exceder 30 caracteres.');
            }
        }

        if (array_key_exists('nombre', $data)) {
            $nombre = trim((string) $data['nombre']);
            if ($nombre === '') {
                throw new InvalidArgumentException('nombre no puede estar vacio.');
            }
            if (mb_strlen($nombre) > 100) {
                throw new InvalidArgumentException('nombre no puede exceder 100 caracteres.');
            }
        }

        if (array_key_exists('tipo', $data)) {
            $this->validateTipo(strtoupper(trim((string) $data['tipo'])));
        }

        foreach (['requiere_periodo', 'genera_mora', 'activo'] as $field) {
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

        if (array_key_exists('codigo', $payload)) {
            $payload['codigo'] = strtoupper(trim((string) $payload['codigo']));
        }

        if (array_key_exists('nombre', $payload)) {
            $payload['nombre'] = trim((string) $payload['nombre']);
        }

        if (array_key_exists('tipo', $payload)) {
            $payload['tipo'] = strtoupper(trim((string) $payload['tipo']));
        }

        foreach (['requiere_periodo', 'genera_mora', 'activo'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = (int) $payload[$field];
            }
        }

        return $payload;
    }

    private function validateTipo(string $tipo): void
    {
        $permitidos = [
            self::TIPO_INSCRIPCION,
            self::TIPO_COLEGIATURA,
            self::TIPO_SERVICIO,
            self::TIPO_MORA,
        ];

        if (!in_array($tipo, $permitidos, true)) {
            throw new InvalidArgumentException('tipo no es valido para conceptos_cobro.');
        }
    }
}
