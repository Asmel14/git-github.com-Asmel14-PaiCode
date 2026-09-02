<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class EstudianteDocumentosModel extends BaseModel
{
    protected string $table = 'estudiante_documentos';

    protected array $primaryKey = ['estudiante_id', 'documento_id'];

    protected array $fillable = [
        'estudiante_id',
        'documento_id',
        'tipo',
        'observaciones',
    ];

    public function getByEstudianteId(int $estudianteId): array
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');

        $sql = 'SELECT * FROM `estudiante_documentos`
                WHERE `estudiante_id` = :estudiante_id
                ORDER BY `created_at` DESC, `documento_id` DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estudiante_id', $estudianteId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByDocumentoId(int $documentoId): array
    {
        $this->validatePositiveId($documentoId, 'documento_id');

        $sql = 'SELECT * FROM `estudiante_documentos`
                WHERE `documento_id` = :documento_id
                ORDER BY `created_at` DESC, `estudiante_id` DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':documento_id', $documentoId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function existsRelacion(int $estudianteId, int $documentoId): bool
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');
        $this->validatePositiveId($documentoId, 'documento_id');

        $row = $this->find([
            'estudiante_id' => $estudianteId,
            'documento_id' => $documentoId,
        ]);

        return $row !== null;
    }

    public function asignarDocumento(array $data): bool
    {
        $this->validateData($data);

        $estudianteId = (int) $data['estudiante_id'];
        $documentoId = (int) $data['documento_id'];

        if ($this->existsRelacion($estudianteId, $documentoId)) {
            return false;
        }

        $sql = 'INSERT INTO `estudiante_documentos`
                (`estudiante_id`, `documento_id`, `tipo`, `observaciones`)
                VALUES (:estudiante_id, :documento_id, :tipo, :observaciones)';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estudiante_id', $estudianteId, PDO::PARAM_INT);
        $stmt->bindValue(':documento_id', $documentoId, PDO::PARAM_INT);
        $stmt->bindValue(':tipo', trim((string) $data['tipo']));

        $observaciones = array_key_exists('observaciones', $data)
            ? trim((string) $data['observaciones'])
            : null;

        if ($observaciones === '') {
            $observaciones = null;
        }

        $stmt->bindValue(':observaciones', $observaciones);
        $stmt->execute();

        return true;
    }

    public function actualizarRelacion(int $estudianteId, int $documentoId, array $data): int
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');
        $this->validatePositiveId($documentoId, 'documento_id');
        $this->validateUpdateData($data);

        if (!$this->existsRelacion($estudianteId, $documentoId)) {
            throw new InvalidArgumentException('La relacion estudiante-documento no existe.');
        }

        $payload = [];
        if (array_key_exists('tipo', $data)) {
            $payload['tipo'] = trim((string) $data['tipo']);
        }

        if (array_key_exists('observaciones', $data)) {
            $obs = $data['observaciones'];
            if ($obs === null) {
                $payload['observaciones'] = null;
            } else {
                $obs = trim((string) $obs);
                $payload['observaciones'] = $obs === '' ? null : $obs;
            }
        }

        return $this->update([
            'estudiante_id' => $estudianteId,
            'documento_id' => $documentoId,
        ], $payload);
    }

    public function quitarDocumento(int $estudianteId, int $documentoId): int
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');
        $this->validatePositiveId($documentoId, 'documento_id');

        return $this->delete([
            'estudiante_id' => $estudianteId,
            'documento_id' => $documentoId,
        ]);
    }

    public function limpiarPorEstudiante(int $estudianteId): int
    {
        $this->validatePositiveId($estudianteId, 'estudiante_id');
        return $this->delete(['estudiante_id' => $estudianteId]);
    }

    private function validateData(array $data): void
    {
        $required = ['estudiante_id', 'documento_id', 'tipo'];
        foreach ($required as $field) {
            if (!array_key_exists($field, $data)) {
                throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
            }
        }

        $this->validatePositiveId((int) $data['estudiante_id'], 'estudiante_id');
        $this->validatePositiveId((int) $data['documento_id'], 'documento_id');

        $tipo = trim((string) $data['tipo']);
        if ($tipo === '') {
            throw new InvalidArgumentException('tipo no puede estar vacio.');
        }

        if (mb_strlen($tipo) > 100) {
            throw new InvalidArgumentException('tipo no puede exceder 100 caracteres.');
        }

        if (array_key_exists('observaciones', $data) && $data['observaciones'] !== null) {
            $obs = trim((string) $data['observaciones']);
            if ($obs !== '' && mb_strlen($obs) > 255) {
                throw new InvalidArgumentException('observaciones no puede exceder 255 caracteres.');
            }
        }
    }

    private function validateUpdateData(array $data): void
    {
        if ($data === []) {
            throw new InvalidArgumentException('Debe enviar al menos un campo para actualizar.');
        }

        if (array_key_exists('tipo', $data)) {
            $tipo = trim((string) $data['tipo']);
            if ($tipo === '') {
                throw new InvalidArgumentException('tipo no puede estar vacio.');
            }
            if (mb_strlen($tipo) > 100) {
                throw new InvalidArgumentException('tipo no puede exceder 100 caracteres.');
            }
        }

        if (array_key_exists('observaciones', $data) && $data['observaciones'] !== null) {
            $obs = trim((string) $data['observaciones']);
            if ($obs !== '' && mb_strlen($obs) > 255) {
                throw new InvalidArgumentException('observaciones no puede exceder 255 caracteres.');
            }
        }
    }

    private function validatePositiveId(int $id, string $field): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException($field . ' debe ser mayor que cero.');
        }
    }
}
