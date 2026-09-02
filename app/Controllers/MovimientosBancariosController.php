<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/MovimientosBancariosModel.php';

class MovimientosBancariosController extends GenericModelController
{
    protected string $modelClass = 'MovimientosBancariosModel';
}
