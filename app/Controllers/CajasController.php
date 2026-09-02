<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/CajasModel.php';

class CajasController extends GenericModelController
{
    protected string $modelClass = 'CajasModel';
}
