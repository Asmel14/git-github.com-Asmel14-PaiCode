<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/MetodosPagoModel.php';

class MetodosPagoController extends GenericModelController
{
    protected string $modelClass = 'MetodosPagoModel';
}
