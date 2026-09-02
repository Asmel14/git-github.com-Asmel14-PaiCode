<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/NivelesModel.php';

class NivelesController extends GenericModelController
{
    protected string $modelClass = 'NivelesModel';
}
