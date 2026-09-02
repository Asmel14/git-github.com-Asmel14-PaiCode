<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/DepartamentosModel.php';

class DepartamentosController extends GenericModelController
{
    protected string $modelClass = 'DepartamentosModel';
}
