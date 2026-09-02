<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class UsuariosModel extends BaseModel
{
    protected string $table = 'usuarios';

    protected array $primaryKey = ['id'];

    protected array $fillable = [
        'nombre_completo',
        'correo',
        'contrasena',
        'estado',
        'ultimo_acceso',
    ];

    public function getById(int $id): ?array
    {
        return $this->find(['id' => $id]);
    }

    public function getByCorreo(string $correo): ?array
    {
        return $this->find(['correo' => strtolower(trim($correo))]);
    }

    public function getActivos(int $limit = 1000): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `usuarios`
                WHERE `estado` = 1
                ORDER BY `nombre_completo` ASC, `id` DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getAllOrdered(int $limit = 2000): array
    {
        $safeLimit = $this->normalizeLimit($limit);
        $sql = 'SELECT * FROM `usuarios`
                ORDER BY `nombre_completo` ASC, `id` DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $safeLimit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createUsuario(array $data): int
    {
        $this->validateData($data);
        $payload = $this->normalizePayload($data);

        if ($this->getByCorreo((string) $payload['correo']) !== null) {
            throw new InvalidArgumentException('Ya existe un usuario con ese correo.');
        }

        if (!array_key_exists('estado', $payload)) {
            $payload['estado'] = 1;
        }

        $payload['contrasena'] = $this->hashContrasena((string) $payload['contrasena']);

        $newId = $this->create($payload);
        if ($newId === null) {
            throw new RuntimeException('No se pudo crear el usuario.');
        }

        return $newId;
    }

    public function updateUsuario(int $id, array $data): int
    {
        $this->validateData($data, true);

        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El usuario indicado no existe.');
        }

        $payload = $this->normalizePayload($data);

        if (array_key_exists('correo', $payload)) {
            $exists = $this->getByCorreo((string) $payload['correo']);
            if ($exists !== null && (int) $exists['id'] !== $id) {
                throw new InvalidArgumentException('Ya existe otro usuario con ese correo.');
            }
        }

        if (array_key_exists('contrasena', $payload)) {
            $payload['contrasena'] = $this->hashContrasena((string) $payload['contrasena']);
        }

        return $this->update(['id' => $id], $payload);
    }

    public function autenticar(string $correo, string $contrasena): ?array
    {
        $correoNorm = strtolower(trim($correo));
        if ($correoNorm === '' || trim($contrasena) === '') {
            return null;
        }

        $usuario = $this->getByCorreo($correoNorm);
        if ($usuario === null) {
            return null;
        }

        if ((int) ($usuario['estado'] ?? 0) !== 1) {
            return null;
        }

        $storedPassword = (string) ($usuario['contrasena'] ?? '');

        if ($this->isPasswordHash($storedPassword)) {
            if (!password_verify($contrasena, $storedPassword)) {
                return null;
            }
        } else {
            // Compatibilidad retroactiva: usuarios viejos en texto plano se migran a hash al autenticar.
            if (!hash_equals($storedPassword, $contrasena)) {
                return null;
            }

            $newHash = $this->hashContrasena($contrasena);
            $this->update(['id' => (int) $usuario['id']], ['contrasena' => $newHash]);
        }

        return $usuario;
    }

    public function updateUltimoAcceso(int $id, ?string $fechaHora = null): int
    {
        $usuario = $this->getById($id);
        if ($usuario === null) {
            throw new InvalidArgumentException('El usuario indicado no existe.');
        }

        $value = $fechaHora ?? date('Y-m-d H:i:s');
        if (!$this->isValidDateTime($value)) {
            throw new InvalidArgumentException('ultimo_acceso debe tener formato Y-m-d H:i:s.');
        }

        return $this->update(['id' => $id], ['ultimo_acceso' => $value]);
    }

    public function activarUsuario(int $id): int
    {
        return $this->setEstado($id, 1);
    }

    public function desactivarUsuario(int $id): int
    {
        return $this->setEstado($id, 0);
    }

    private function setEstado(int $id, int $estado): int
    {
        $current = $this->getById($id);
        if ($current === null) {
            throw new InvalidArgumentException('El usuario indicado no existe.');
        }

        if ((int) ($current['estado'] ?? -1) === $estado) {
            return 0;
        }

        return $this->update(['id' => $id], ['estado' => $estado]);
    }

    private function validateData(array $data, bool $isPartial = false): void
    {
        $required = ['nombre_completo', 'correo', 'contrasena'];
        if (!$isPartial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
                }
            }
        }

        if (array_key_exists('nombre_completo', $data)) {
            $nombre = trim((string) $data['nombre_completo']);
            if ($nombre === '') {
                throw new InvalidArgumentException('nombre_completo no puede estar vacio.');
            }
            if (mb_strlen($nombre) > 150) {
                throw new InvalidArgumentException('nombre_completo no puede exceder 150 caracteres.');
            }
        }

        if (array_key_exists('correo', $data)) {
            $correo = strtolower(trim((string) $data['correo']));
            if ($correo === '') {
                throw new InvalidArgumentException('correo no puede estar vacio.');
            }
            if (mb_strlen($correo) > 150) {
                throw new InvalidArgumentException('correo no puede exceder 150 caracteres.');
            }
            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException('correo no tiene un formato valido.');
            }
        }

        if (array_key_exists('contrasena', $data)) {
            $pwd = (string) $data['contrasena'];
            if (trim($pwd) === '') {
                throw new InvalidArgumentException('contrasena no puede estar vacia.');
            }
            if (mb_strlen($pwd) < 8) {
                throw new InvalidArgumentException('contrasena debe tener al menos 8 caracteres.');
            }
            if (mb_strlen($pwd) > 255) {
                throw new InvalidArgumentException('contrasena no puede exceder 255 caracteres.');
            }
        }

        if (array_key_exists('estado', $data)) {
            $estado = (int) $data['estado'];
            if (!in_array($estado, [0, 1], true)) {
                throw new InvalidArgumentException('estado solo permite 0 o 1.');
            }
        }

        if (array_key_exists('ultimo_acceso', $data) && $data['ultimo_acceso'] !== null) {
            $ua = trim((string) $data['ultimo_acceso']);
            if ($ua !== '' && !$this->isValidDateTime($ua)) {
                throw new InvalidArgumentException('ultimo_acceso debe tener formato Y-m-d H:i:s.');
            }
        }
    }

    private function normalizePayload(array $data): array
    {
        $payload = $data;

        if (array_key_exists('nombre_completo', $payload)) {
            $payload['nombre_completo'] = trim((string) $payload['nombre_completo']);
        }

        if (array_key_exists('correo', $payload)) {
            $payload['correo'] = strtolower(trim((string) $payload['correo']));
        }

        if (array_key_exists('contrasena', $payload)) {
            $payload['contrasena'] = (string) $payload['contrasena'];
        }

        if (array_key_exists('estado', $payload)) {
            $payload['estado'] = (int) $payload['estado'];
        }

        if (array_key_exists('ultimo_acceso', $payload) && $payload['ultimo_acceso'] !== null) {
            $ua = trim((string) $payload['ultimo_acceso']);
            $payload['ultimo_acceso'] = $ua === '' ? null : $ua;
        }

        return $payload;
    }

    private function hashContrasena(string $plainPassword): string
    {
        $hash = password_hash($plainPassword, PASSWORD_DEFAULT);
        if ($hash === false) {
            throw new RuntimeException('No se pudo generar el hash de la contrasena.');
        }

        return $hash;
    }

    private function isValidDateTime(string $dateTime): bool
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);
        return $dt !== false && $dt->format('Y-m-d H:i:s') === $dateTime;
    }

    private function isPasswordHash(string $value): bool
    {
        if ($value === '') {
            return false;
        }

        $info = password_get_info($value);
        return (int) ($info['algo'] ?? 0) !== 0;
    }

    private function normalizeLimit(int $limit): int
    {
        if ($limit < 1) {
            return 1;
        }
        if ($limit > 5000) {
            return 5000;
        }

        return $limit;
    }
}
