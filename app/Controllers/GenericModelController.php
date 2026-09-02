<?php

declare(strict_types=1);

require_once __DIR__ . '/../Auth/AuthService.php';
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/BaseModel.php';
require_once __DIR__ . '/../Models/AuditoriaModel.php';

abstract class GenericModelController extends BaseController
{
    protected string $modelClass = '';

    protected function makeModel(): BaseModel
    {
        if ($this->modelClass === '') {
            throw new RuntimeException('modelClass no fue definido en el controller.');
        }

        if (!class_exists($this->modelClass)) {
            throw new RuntimeException('No existe la clase de modelo: ' . $this->modelClass);
        }

        $model = new $this->modelClass();
        if (!($model instanceof BaseModel)) {
            throw new RuntimeException('El modelo debe extender BaseModel.');
        }

        return $model;
    }

    protected function getAuditTableName(): string
    {
        $model = $this->makeModel();
        $reflection = new ReflectionObject($model);

        if ($reflection->hasProperty('table')) {
            $property = $reflection->getProperty('table');
            $property->setAccessible(true);
            $table = trim((string) $property->getValue($model));
            if ($table !== '') {
                return $table;
            }
        }

        return $this->modelClass !== '' ? strtolower($this->modelClass) : 'auditoria';
    }

    protected function logAuditEvent(string $accion, array $context = []): void
    {
        try {
            $tabla = $this->getAuditTableName();
            if ($tabla === 'auditoria') {
                return;
            }

            $user = AuthService::user();
            $usuarioId = is_array($user) ? (int) ($user['id'] ?? 0) : 0;
            if ($usuarioId <= 0) {
                $usuarioId = null;
            }

            $registroId = array_key_exists('registro_id', $context) && $context['registro_id'] !== null
                ? (int) $context['registro_id']
                : null;

            $descripcion = $this->buildAuditDescription($context);
            if (strlen($descripcion) > 1000) {
                $descripcion = substr($descripcion, 0, 1000);
            }

            $auditoria = new AuditoriaModel();
            $auditoria->logEvent([
                'usuario_id' => $usuarioId,
                'modulo' => $tabla,
                'accion' => strtoupper(trim($accion)),
                'tabla' => $tabla,
                'registro_id' => $registroId,
                'descripcion' => $descripcion,
                'ip' => $this->getClientIpAddress(),
            ]);
        } catch (Throwable $exception) {
            // La auditoria no debe interrumpir la operacion principal.
        }
    }

    protected function buildAuditDescription(array $context): string
    {
        if ($context === []) {
            return '';
        }

        $parts = [];
        foreach ($context as $key => $value) {
            if ($key === 'registro_id') {
                continue;
            }

            $parts[] = $key . '=' . $this->stringifyAuditValue($value);
        }

        return implode('; ', $parts);
    }

    protected function stringifyAuditValue(mixed $value): string
    {
        if ($value === null) {
            return 'null';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        $encoded = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if (is_string($encoded) && $encoded !== '') {
            return $encoded;
        }

        return '[no serializable]';
    }

    protected function getClientIpAddress(): ?string
    {
        $ip = trim((string) ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? ''));
        if ($ip !== '') {
            $firstIp = trim(explode(',', $ip)[0]);
            if ($firstIp !== '') {
                return substr($firstIp, 0, 45);
            }
        }

        $remoteAddr = trim((string) ($_SERVER['REMOTE_ADDR'] ?? ''));
        if ($remoteAddr === '') {
            return null;
        }

        return substr($remoteAddr, 0, 45);
    }

    public function index(int $limit = 100, int $offset = 0): void
    {
        try {
            $rows = $this->makeModel()->all($limit, $offset);
            $this->success($rows);
            $this->logAuditEvent('CONSULTA', [
                'limit' => $limit,
                'offset' => $offset,
                'registros' => count($rows),
            ]);
        } catch (Throwable $exception) {
            $this->error($exception->getMessage(), 500);
        }
    }

    public function show(array $criteria): void
    {
        try {
            $row = $this->makeModel()->find($criteria);
            if ($row === null) {
                $this->error('Registro no encontrado.', 404);
                return;
            }

            $this->success($row);
            $this->logAuditEvent('CONSULTA', [
                'registro_id' => $row['id'] ?? ($criteria['id'] ?? null),
                'criteria' => $criteria,
            ]);
        } catch (Throwable $exception) {
            $this->error($exception->getMessage(), 400);
        }
    }

    public function store(array $data): void
    {
        try {
            $id = $this->makeModel()->create($data);
            $this->success([
                'id' => $id,
            ], 'Registro creado.', 201);
            $this->logAuditEvent('CREAR', [
                'registro_id' => $id,
                'campos' => array_keys($data),
            ]);
        } catch (Throwable $exception) {
            $this->error($exception->getMessage(), 400);
        }
    }

    public function update(array $criteria, array $data): void
    {
        try {
            $affected = $this->makeModel()->update($criteria, $data);
            $this->success([
                'affected' => $affected,
            ], 'Registro actualizado.');
            if ($affected > 0) {
                $this->logAuditEvent('ACTUALIZAR', [
                    'registro_id' => $criteria['id'] ?? null,
                    'criteria' => $criteria,
                    'campos' => array_keys($data),
                    'afectados' => $affected,
                ]);
            }
        } catch (Throwable $exception) {
            $this->error($exception->getMessage(), 400);
        }
    }

    public function destroy(array $criteria): void
    {
        try {
            $affected = $this->makeModel()->delete($criteria);
            $this->success([
                'affected' => $affected,
            ], 'Registro eliminado.');
            if ($affected > 0) {
                $this->logAuditEvent('ELIMINAR', [
                    'registro_id' => $criteria['id'] ?? null,
                    'criteria' => $criteria,
                    'afectados' => $affected,
                ]);
            }
        } catch (Throwable $exception) {
            $this->error($exception->getMessage(), 400);
        }
    }
}
