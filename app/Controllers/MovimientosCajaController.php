<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/MovimientosCajaModel.php';

class MovimientosCajaController extends GenericModelController
{
    protected string $modelClass = 'MovimientosCajaModel';
}
