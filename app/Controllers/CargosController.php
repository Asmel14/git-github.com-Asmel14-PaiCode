<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/CargosModel.php';

class CargosController extends GenericModelController
{
    protected string $modelClass = 'CargosModel';
}
