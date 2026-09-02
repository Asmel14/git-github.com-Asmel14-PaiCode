<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/PeriodosCobroModel.php';

class PeriodosCobroController extends GenericModelController
{
    protected string $modelClass = 'PeriodosCobroModel';
}
