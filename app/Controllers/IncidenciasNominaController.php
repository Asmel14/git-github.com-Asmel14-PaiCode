<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/IncidenciasNominaModel.php';

class IncidenciasNominaController extends GenericModelController
{
    protected string $modelClass = 'IncidenciasNominaModel';
}
