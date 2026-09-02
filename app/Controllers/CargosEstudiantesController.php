<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/CargosEstudiantesModel.php';

class CargosEstudiantesController extends GenericModelController
{
    protected string $modelClass = 'CargosEstudiantesModel';
}
