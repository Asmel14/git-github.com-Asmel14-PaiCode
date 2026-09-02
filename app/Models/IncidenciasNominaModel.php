<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class IncidenciasNominaModel extends BaseModel
{
    protected string $table = 'incidencias_nomina';

    protected array $primaryKey = ['id'];

    private const TIPO_AUSENCIA = 'AUSENCIA';
    private const TIPO_TARDANZA = 'TARDANZA';
    private const TIPO_PERMISO = 'PERMISO';
    private const TIPO_LICENCIA = 'LICENCIA';
    private const TIPO_HORAS_EXTRAS = 'HORAS_EXTRAS';
    private const TIPO_OTRA = 'OTRA';

    private const ESTADO_PENDIENTE = 'PENDIENTE';
    private const ESTADO_APROBADA = 'APROBADA';
    private const ESTADO_RECHAZADA = 'RECHAZADA';
    private const ESTADO_APLICADA = 'APLICADA';

    protected array $fillable = [
        'personal_id',
        'fecha',
        'tipo',
        'cantidad',
        'horas',
        'justificada',
        'observaciones',
        'estado',
        'usuario_id',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByPersonalId(int $personalId, int $limit = 300): array
    {
        $this->validatePositiveId($personalId, 'personal_id');
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `incidencias_nomina`
                WHERE `personal_id` = :personal_id
                ORDER BY `fecha` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':personal_id', $personalId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByEstado(string $estado, int $limit = 500): array
    {
        $estadoNormalizado = strtoupper(trim($estado));
        $this->validateEstado($estadoNormalizado);
        $safeLimit = $this->normalizeLimit($limit);

        $sql = 'SELECT * FROM `incidencias_nomina`
                WHERE `estado` = :estado
                ORDER BY `fecha` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estado', $estadoNormalizado);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByRangoFechas(string $fechaInicio, string $fechaFin, int $limit = 1000): array
    {
        if (!$this->isValidDate($fechaInicio) || !$this->isValidDate($fechaFin)) {
            throw new InvalidArgumentException('fechaInicio y fechaFin deben tener formato Y-m-d.');
        }

        if ($fechaInicio > $fechaFin) {
            throw new InvalidArgumentException('fechaInicio no puede ser mayor que fechaFin.');
        }

        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `incidencias_nomina`
                WHERE `fecha` >= :fecha_inicio AND `fecha` <= :fecha_fin
                ORDER BY `fecha` DESC, `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':fecha_inicio', $fechaInicio);
        $stmt->bindValue(':fecha_fin', $fechaFin);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createIncidencia(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        if (!array_key_exists('estado', $payload)) {
            $payload['estado'] = self::ESTADO_PENDIENTE;
        }

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear la incidencia de nomina.');
        }

        return $newId;
    }

    public function updateIncidencia(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('La incidencia indicada no existe.');
        }

        if (($current['estado'] ?? null) === self::ESTADO_APLICADA) {
            throw new InvalidArgumentException('No se puede editar una incidencia en estado APLICADA.');
        }

        $payload = $this->normalizePayload($data);
        return $this->update(['id' => $id], $payload);
    }

    public function cambiarEstado(int $id, string $estado, ?int $usuarioId = null): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('La incidencia indicada no existe.');
        }

        $estadoNormalizado = strtoupper(trim($estado));
        $this->validateEstado($estadoNormalizado);

        $payload = ['estado' => $estadoNormalizado];
        if ($usuarioId !== null) {
            $this->validatePositiveId($usuarioId, 'usuario_id');
            $payload['usuario_id'] = $usuarioId;
        }

        return $this->update(['id' => $id], $payload);
    }

    public function aprobar(int $id, ?int $usuarioId = null): int
    {
        return $this->cambiarEstado($id, self::ESTADO_APROBADA, $usuarioId);
    }

    public function rechazar(int $id, ?int $usuarioId = null): int
    {
        return $this->cambiarEstado($id, self::ESTADO_RECHAZADA, $usuarioId);
    }

    public function aplicar(int $id, ?int $usuarioId = null): int
    {
        return $this->cambiarEstado($id, self::ESTADO_APLICADA, $usuarioId);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['personal_id', 'fecha', 'tipo'];

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

        if (array_key_exists('fecha', $data)) {
            $fecha = trim((string) $data['fecha']);
            if ($fecha === '' || !$this->isValidDate($fecha)) {
                throw new InvalidArgumentException('fecha debe tener formato Y-m-d.');
            }
        }

        if (array_key_exists('tipo', $data)) {
            $tipo = strtoupper(trim((string) $data['tipo']));
            if ($tipo === '') {
                throw new InvalidArgumentException('tipo no puede estar vacio.');
            }
            $this->validateTipo($tipo);
        }

        if (array_key_exists('cantidad', $data)) {
            $cantidad = round((float) $data['cantidad'], 2);
            if ($cantidad < 0) {
                throw new InvalidArgumentException('cantidad no puede ser negativa.');
            }
        }

        if (array_key_exists('horas', $data)) {
            $horas = round((float) $data['horas'], 2);
            if ($horas < 0) {
                throw new InvalidArgumentException('horas no puede ser negativa.');
            }
        }

        if (array_key_exists('justificada', $data)) {
            $justificada = (int) $data['justificada'];
            if (!in_array($justificada, [0, 1], true)) {
                throw new InvalidArgumentException('justificada solo permite 0 o 1.');
            }
        }

        if (array_key_exists('estado', $data)) {
            $estado = strtoupper(trim((string) $data['estado']));
            if ($estado === '') {
                throw new InvalidArgumentException('estado no puede estar vacio.');
            }
            $this->validateEstado($estado);
        }

        if (array_key_exists('usuario_id', $data) && $data['usuario_id'] !== null) {
            $this->validatePositiveId((int) $data['usuario_id'], 'usuario_id');
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

        if (array_key_exists('personal_id', $payload)) {
            $payload['personal_id'] = (int) $payload['personal_id'];
        }

        if (array_key_exists('fecha', $payload)) {
            $payload['fecha'] = trim((string) $payload['fecha']);
        }

        if (array_key_exists('tipo', $payload)) {
            $payload['tipo'] = strtoupper(trim((string) $payload['tipo']));
        }

        if (array_key_exists('cantidad', $payload)) {
            $payload['cantidad'] = round((float) $payload['cantidad'], 2);
        }

        if (array_key_exists('horas', $payload)) {
            $payload['horas'] = round((float) $payload['horas'], 2);
        }

        if (array_key_exists('justificada', $payload)) {
            $payload['justificada'] = (int) $payload['justificada'];
        }

        if (array_key_exists('estado', $payload)) {
            $payload['estado'] = strtoupper(trim((string) $payload['estado']));
        }

        if (array_key_exists('usuario_id', $payload) && $payload['usuario_id'] !== null) {
            $payload['usuario_id'] = (int) $payload['usuario_id'];
        }

        if (array_key_exists('observaciones', $payload) && $payload['observaciones'] !== null) {
            $obs = trim((string) $payload['observaciones']);
            $payload['observaciones'] = $obs === '' ? null : $obs;
        }

        return $payload;
    }

    private function validateTipo(string $tipo): void
    {
        $permitidos = [
            self::TIPO_AUSENCIA,
            self::TIPO_TARDANZA,
            self::TIPO_PERMISO,
            self::TIPO_LICENCIA,
            self::TIPO_HORAS_EXTRAS,
            self::TIPO_OTRA,
        ];

        if (!in_array($tipo, $permitidos, true)) {
            throw new InvalidArgumentException('tipo no es valido para incidencias_nomina.');
        }
    }

    private function validateEstado(string $estado): void
    {
        $permitidos = [
            self::ESTADO_PENDIENTE,
            self::ESTADO_APROBADA,
            self::ESTADO_RECHAZADA,
            self::ESTADO_APLICADA,
        ];

        if (!in_array($estado, $permitidos, true)) {
            throw new InvalidArgumentException('estado no es valido para incidencias_nomina.');
        }
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

        if ($limit > 3000) {
            return 3000;
        }

        return $limit;
    }
}
