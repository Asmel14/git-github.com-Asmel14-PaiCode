<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/PagosNominaModel.php';

class PagosNominaController extends GenericModelController
{
    protected string $modelClass = 'PagosNominaModel';
}
