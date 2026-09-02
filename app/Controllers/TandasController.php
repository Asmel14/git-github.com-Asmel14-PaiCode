<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/TandasModel.php';

class TandasController extends GenericModelController
{
    protected string $modelClass = 'TandasModel';

    public function store(array $data): void
    {
        try {
            /** @var TandasModel $model */
            $model = $this->makeModel();
            $id = $model->createTanda($data);
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
                throw new InvalidArgumentException('Para tandas, se requiere criteria.id valido.');
            }

            /** @var TandasModel $model */
            $model = $this->makeModel();
            $affected = $model->updateTanda($id, $data);
            $this->success(['affected' => $affected], 'Registro actualizado.');
        } catch (Throwable $exception) {
            $this->error($exception->getMessage(), 400);
        }
    }
}
