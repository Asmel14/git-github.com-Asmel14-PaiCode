<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/CuentasBancariasModel.php';

class CuentasBancariasController extends GenericModelController
{
    protected string $modelClass = 'CuentasBancariasModel';
}
