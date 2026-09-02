<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/ParametrosFinancierosModel.php';

class ParametrosFinancierosController extends GenericModelController
{
    protected string $modelClass = 'ParametrosFinancierosModel';
}
