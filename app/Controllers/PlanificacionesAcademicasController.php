<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/PlanificacionesAcademicasModel.php';

class PlanificacionesAcademicasController extends GenericModelController
{
    protected string $modelClass = 'PlanificacionesAcademicasModel';
}
