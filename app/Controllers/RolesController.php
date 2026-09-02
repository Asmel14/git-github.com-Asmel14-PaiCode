<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/RolesModel.php';

class RolesController extends GenericModelController
{
    protected string $modelClass = 'RolesModel';
}
