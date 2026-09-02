<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/NominaHistorialModel.php';

class NominaHistorialController extends GenericModelController
{
    protected string $modelClass = 'NominaHistorialModel';
}
