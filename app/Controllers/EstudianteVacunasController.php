<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/EstudianteVacunasModel.php';

class EstudianteVacunasController extends GenericModelController
{
    protected string $modelClass = 'EstudianteVacunasModel';
}
