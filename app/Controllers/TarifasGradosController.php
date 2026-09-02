<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/TarifasGradosModel.php';

class TarifasGradosController extends GenericModelController
{
    protected string $modelClass = 'TarifasGradosModel';
}
