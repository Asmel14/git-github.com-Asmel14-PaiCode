<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/HistorialSalariosModel.php';

class HistorialSalariosController extends GenericModelController
{
    protected string $modelClass = 'HistorialSalariosModel';
}
