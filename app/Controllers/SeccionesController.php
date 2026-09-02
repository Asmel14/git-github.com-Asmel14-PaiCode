<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/SeccionesModel.php';

class SeccionesController extends GenericModelController
{
    protected string $modelClass = 'SeccionesModel';
}
