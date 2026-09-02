<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/DireccionesEstudiantesModel.php';

class DireccionesEstudiantesController extends GenericModelController
{
    protected string $modelClass = 'DireccionesEstudiantesModel';
}
