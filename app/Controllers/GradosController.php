<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/GradosModel.php';

class GradosController extends GenericModelController
{
    protected string $modelClass = 'GradosModel';

    public function store(array $data): void
    {
        try {
            /** @var GradosModel $model */
            $model = $this->makeModel();
            $id = $model->createGrado($data);
            $this->success(['id' => $id], 'Registro creado.', 201);
        } catch (Throwable $exception) {
            $message = $exception->getMessage();
            if (stripos($message, 'Duplicate entry') !== false) {
                $message = 'Ya existe un registro con esos datos.';
            }

            $this->error($message, 400);
        }
    }

    public function update(array $criteria, array $data): void
    {
        try {
            $id = (int) ($criteria['id'] ?? 0);
            if ($id <= 0) {
                throw new InvalidArgumentException('Para grados, se requiere criteria.id valido.');
            }

            /** @var GradosModel $model */
            $model = $this->makeModel();
            $affected = $model->updateGrado($id, $data);
            $this->success(['affected' => $affected], 'Registro actualizado.');
        } catch (Throwable $exception) {
            $message = $exception->getMessage();
            if (stripos($message, 'Duplicate entry') !== false) {
                $message = 'Ya existe un registro con esos datos.';
            }

            $this->error($message, 400);
        }
    }
}
