<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/PermisosModel.php';

class PermisosController extends GenericModelController
{
    protected string $modelClass = 'PermisosModel';
}
