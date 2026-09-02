<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/EstudiosProcesoModel.php';

class EstudiosProcesoController extends GenericModelController
{
    protected string $modelClass = 'EstudiosProcesoModel';
}
