<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/RolPermisosModel.php';

class RolPermisosController extends GenericModelController
{
    protected string $modelClass = 'RolPermisosModel';
}
