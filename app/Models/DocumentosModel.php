<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class DocumentosModel extends BaseModel
{
    protected string $table = 'documentos';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'nombre',
        'nombre_archivo',
        'ruta',
        'extension',
        'mime_type',
        'tamano',
        'tipo_documento',
        'descripcion',
        'usuario_id',
        'estado',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getActivos(int $limit = 500): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `documentos` WHERE `estado` = 1 ORDER BY `id` DESC LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByTipoDocumento(string $tipoDocumento, int $limit = 500): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `documentos`
                WHERE `tipo_documento` = :tipo_documento
                ORDER BY `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':tipo_documento', trim($tipoDocumento));
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByUsuarioId(int $usuarioId, int $limit = 500): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `documentos`
                WHERE `usuario_id` = :usuario_id
                ORDER BY `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createDocumento(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        if (!array_key_exists('estado', $payload)) {
            $payload['estado'] = 1;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el documento.');
        }

        return $newId;
    }

    public function updateDocumento(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El documento indicado no existe.');
        }

        $payload = $this->normalizePayload($data);
        return $this->update(['id' => $id], $payload);
    }

    public function desactivarDocumento(int $id): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El documento indicado no existe.');
        }

        return $this->update(['id' => $id], ['estado' => 0]);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['nombre', 'nombre_archivo', 'ruta'];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data) || trim((string) $data[$field]) === '') {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        $rules = [
            'nombre' => 255,
            'nombre_archivo' => 255,
            'ruta' => 500,
            'extension' => 20,
            'mime_type' => 100,
            'tipo_documento' => 100,
            'descripcion' => 255,
        ];

        foreach ($rules as $field => $maxLength) {
            if (!array_key_exists($field, $data) || $data[$field] === null) {
                continue;
            }

            $value = trim((string) $data[$field]);
            if (in_array($field, $required, true) && $value === '') {
                throw new InvalidArgumentException($field . ' no puede estar vacio.');
            }

            if ($value !== '' && mb_strlen($value) > $maxLength) {
                throw new InvalidArgumentException($field . ' no puede exceder ' . $maxLength . ' caracteres.');
            }
        }

        if (array_key_exists('tamano', $data) && $data['tamano'] !== null) {
            $tamano = (int) $data['tamano'];
            if ($tamano < 0) {
                throw new InvalidArgumentException('tamano no puede ser negativo.');
            }
        }

        if (array_key_exists('usuario_id', $data) && $data['usuario_id'] !== null && (int) $data['usuario_id'] <= 0) {
            throw new InvalidArgumentException('usuario_id debe ser mayor que cero o null.');
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
        $textFields = [
            'nombre',
            'nombre_archivo',
            'ruta',
            'extension',
            'mime_type',
            'tipo_documento',
            'descripcion',
        ];

        foreach ($textFields as $field) {
            if (!array_key_exists($field, $payload) || $payload[$field] === null) {
                continue;
            }

            $value = trim((string) $payload[$field]);
            if ($value === '') {
                $payload[$field] = in_array($field, ['nombre', 'nombre_archivo', 'ruta'], true) ? $value : null;
                continue;
            }

            if ($field === 'extension') {
                $payload[$field] = strtolower($value);
            } else {
                $payload[$field] = $value;
            }
        }

        if (array_key_exists('tamano', $payload) && $payload['tamano'] !== null) {
            $payload['tamano'] = (int) $payload['tamano'];
        }

        if (array_key_exists('usuario_id', $payload) && $payload['usuario_id'] !== null) {
            $payload['usuario_id'] = (int) $payload['usuario_id'];
        }

        if (array_key_exists('estado', $payload)) {
            $payload['estado'] = (int) $payload['estado'];
        }

        return $payload;
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
