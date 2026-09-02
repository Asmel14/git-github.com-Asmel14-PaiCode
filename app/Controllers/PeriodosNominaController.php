<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/PeriodosNominaModel.php';

class PeriodosNominaController extends GenericModelController
{
    protected string $modelClass = 'PeriodosNominaModel';
}
