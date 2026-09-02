<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class AniosEscolaresModel extends BaseModel
{
    protected string $table = 'anios_escolares';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByNombre(string $nombre): ?array
    {
        return $this->find(['nombre' => $nombre]);
    }

    public function getAllOrdered(): array
    {
        $sql = 'SELECT * FROM `anios_escolares` ORDER BY `fecha_inicio` DESC, `id` DESC';
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    public function getVigente(?string $fecha = null): ?array
    {
        $fechaReferencia = $fecha ?? date('Y-m-d');
        $sql = 'SELECT * FROM `anios_escolares`
            WHERE `fecha_inicio` <= :fecha_inicio AND `fecha_fin` >= :fecha_fin
                ORDER BY `fecha_inicio` DESC
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':fecha_inicio', $fechaReferencia);
        $stmt->bindValue(':fecha_fin', $fechaReferencia);
        $stmt->execute();

        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function createAnio(array $data): int
    {
        $this->validateData($data);

        $newId = $this->create($data);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el anio escolar.');
        }

        return $newId;
    }

    public function updateAnio(int $id, array $data): int
    {
        $this->validateData($data, true);
        return $this->update(['id' => $id], $data);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['nombre', 'fecha_inicio', 'fecha_fin'];

        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data) || trim((string) $data[$field]) === '') {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        $fechaInicio = (string) ($data['fecha_inicio'] ?? '');
        $fechaFin = (string) ($data['fecha_fin'] ?? '');

        if ($fechaInicio !== '' && !$this->isValidDate($fechaInicio)) {
            throw new InvalidArgumentException('fecha_inicio no tiene formato valido Y-m-d.');
        }

        if ($fechaFin !== '' && !$this->isValidDate($fechaFin)) {
            throw new InvalidArgumentException('fecha_fin no tiene formato valido Y-m-d.');
        }

        if ($fechaInicio !== '' && $fechaFin !== '' && $fechaInicio > $fechaFin) {
            throw new InvalidArgumentException('fecha_inicio no puede ser mayor que fecha_fin.');
        }
    }

    private function isValidDate(string $date): bool
    {
        $dt = DateTime::createFromFormat('Y-m-d', $date);
        return $dt !== false && $dt->format('Y-m-d') === $date;
    }
}
