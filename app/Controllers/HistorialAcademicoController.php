<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/HistorialAcademicoModel.php';

class HistorialAcademicoController extends GenericModelController
{
    protected string $modelClass = 'HistorialAcademicoModel';
}
