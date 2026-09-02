<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/NominaDetallesModel.php';

class NominaDetallesController extends GenericModelController
{
    protected string $modelClass = 'NominaDetallesModel';
}
