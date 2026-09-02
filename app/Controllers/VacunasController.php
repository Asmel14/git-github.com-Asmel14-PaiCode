<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/VacunasModel.php';

class VacunasController extends GenericModelController
{
    protected string $modelClass = 'VacunasModel';
}
