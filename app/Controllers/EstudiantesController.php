<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/EstudiantesModel.php';

class EstudiantesController extends GenericModelController
{
    protected string $modelClass = 'EstudiantesModel';
}
