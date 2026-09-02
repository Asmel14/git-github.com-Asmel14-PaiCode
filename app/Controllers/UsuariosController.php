<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/UsuariosModel.php';

class UsuariosController extends GenericModelController
{
    protected string $modelClass = 'UsuariosModel';

    public function index(int $limit = 100, int $offset = 0): void
    {
        try {
            $rows = $this->makeModel()->all($limit, $offset);
            $sanitized = array_map([$this, 'sanitizeUserRow'], $rows);
            $this->success($sanitized);
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

            $this->success($this->sanitizeUserRow($row));
        } catch (Throwable $exception) {
            $this->error($exception->getMessage(), 400);
        }
    }

    public function store(array $data): void
    {
        try {
            /** @var UsuariosModel $model */
            $model = $this->makeModel();
            $id = $model->createUsuario($data);
            $this->success(['id' => $id], 'Registro creado.', 201);
        } catch (Throwable $exception) {
            $this->error($exception->getMessage(), 400);
        }
    }

    public function update(array $criteria, array $data): void
    {
        try {
            $id = (int) ($criteria['id'] ?? 0);
            if ($id <= 0) {
                throw new InvalidArgumentException('Para usuarios, se requiere criteria.id valido.');
            }

            /** @var UsuariosModel $model */
            $model = $this->makeModel();
            $affected = $model->updateUsuario($id, $data);
            $this->success(['affected' => $affected], 'Registro actualizado.');
        } catch (Throwable $exception) {
            $this->error($exception->getMessage(), 400);
        }
    }

    private function sanitizeUserRow(array $row): array
    {
        unset($row['contrasena']);
        return $row;
    }
}
