<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class CajaSesionesModel extends BaseModel
{
    protected string $table = 'caja_sesiones';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'caja_id',
        'usuario_apertura_id',
        'usuario_cierre_id',
        'fecha_apertura',
        'fecha_cierre',
        'monto_inicial',
        'total_ingresos',
        'total_egresos',
        'monto_esperado',
        'monto_contado',
        'diferencia',
        'estado',
        'observaciones',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getAllOrdered(int $limit = 200): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `caja_sesiones` ORDER BY `id` DESC LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByCaja(int $cajaId, int $limit = 200): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `caja_sesiones`
                WHERE `caja_id` = :caja_id
                ORDER BY `id` DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':caja_id', $cajaId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getSesionAbiertaByCaja(int $cajaId): ?array
    {
        $sql = 'SELECT * FROM `caja_sesiones`
                WHERE `caja_id` = :caja_id AND `estado` = :estado
                ORDER BY `id` DESC
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':caja_id', $cajaId, PDO::PARAM_INT);
        $stmt->bindValue(':estado', 'ABIERTA');
        $stmt->execute();

        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function abrirSesion(array $data): int
    {
        $this->validateApertura($data);

        $cajaId = (int) $data['caja_id'];
        if ($this->getSesionAbiertaByCaja($cajaId) !== null) {
            throw new InvalidArgumentException('La caja ya tiene una sesion ABIERTA.');
        }

        $montoInicial = $this->normalizeAmount($data['monto_inicial'] ?? 0);
        $payload = [
            'caja_id' => $cajaId,
            'usuario_apertura_id' => (int) $data['usuario_apertura_id'],
            'monto_inicial' => $montoInicial,
            'total_ingresos' => 0,
            'total_egresos' => 0,
            'monto_esperado' => $montoInicial,
            'estado' => 'ABIERTA',
            'observaciones' => $data['observaciones'] ?? null,
        ];

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo abrir la sesion de caja.');
        }

        return $newId;
    }

    public function cerrarSesion(int $sesionId, array $data): int
    {
        $sesion = $this->getById($sesionId);
        if ($sesion === null) {
            throw new InvalidArgumentException('La sesion de caja no existe.');
        }

        if (($sesion['estado'] ?? null) !== 'ABIERTA') {
            throw new InvalidArgumentException('Solo se puede cerrar una sesion en estado ABIERTA.');
        }

        $this->validateCierre($data);

        $montoInicial = $this->normalizeAmount($sesion['monto_inicial'] ?? 0);
        $ingresos = $this->normalizeAmount($sesion['total_ingresos'] ?? 0);
        $egresos = $this->normalizeAmount($sesion['total_egresos'] ?? 0);
        $montoEsperado = $this->normalizeAmount($montoInicial + $ingresos - $egresos);
        $montoContado = $this->normalizeAmount($data['monto_contado']);
        $diferencia = $this->normalizeAmount($montoContado - $montoEsperado);

        $payload = [
            'usuario_cierre_id' => (int) $data['usuario_cierre_id'],
            'fecha_cierre' => date('Y-m-d H:i:s'),
            'monto_esperado' => $montoEsperado,
            'monto_contado' => $montoContado,
            'diferencia' => $diferencia,
            'estado' => 'CERRADA',
        ];

        if (array_key_exists('observaciones', $data)) {
            $payload['observaciones'] = $data['observaciones'];
        }

        return $this->update(['id' => $sesionId], $payload);
    }

    public function anularSesion(int $sesionId, ?string $observaciones = null): int
    {
        $sesion = $this->getById($sesionId);
        if ($sesion === null) {
            throw new InvalidArgumentException('La sesion de caja no existe.');
        }

        if (($sesion['estado'] ?? null) === 'ANULADA') {
            return 0;
        }

        return $this->update(['id' => $sesionId], [
            'estado' => 'ANULADA',
            'observaciones' => $observaciones,
        ]);
    }

    private function validateApertura(array $data): void
    {
        $required = ['caja_id', 'usuario_apertura_id'];
        foreach ($required as $field) {
            if (!array_key_exists($field, $data) || (int) $data[$field] <= 0) {
                throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio y debe ser mayor que cero.');
            }
        }

        if (array_key_exists('monto_inicial', $data) && $this->normalizeAmount($data['monto_inicial']) < 0) {
            throw new InvalidArgumentException('monto_inicial no puede ser negativo.');
        }

        if (array_key_exists('observaciones', $data) && $data['observaciones'] !== null) {
            if (trim((string) $data['observaciones']) === '') {
                throw new InvalidArgumentException('observaciones no puede ser una cadena vacia.');
            }
        }
    }

    private function validateCierre(array $data): void
    {
        $required = ['usuario_cierre_id', 'monto_contado'];
        foreach ($required as $field) {
            if (!array_key_exists($field, $data)) {
                throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
            }
        }

        if ((int) $data['usuario_cierre_id'] <= 0) {
            throw new InvalidArgumentException('usuario_cierre_id debe ser mayor que cero.');
        }

        if ($this->normalizeAmount($data['monto_contado']) < 0) {
            throw new InvalidArgumentException('monto_contado no puede ser negativo.');
        }
    }

    private function normalizeAmount(mixed $value): float
    {
        return round((float) $value, 2);
    }

    private function normalizeLimit(int $limit): int
    {
        if ($limit < 1) {
            return 1;
        }

        if ($limit > 1000) {
            return 1000;
        }

        return $limit;
    }
}
