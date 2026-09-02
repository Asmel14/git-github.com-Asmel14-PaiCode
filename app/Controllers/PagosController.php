<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/PagosModel.php';

class PagosController extends GenericModelController
{
    protected string $modelClass = 'PagosModel';
}
