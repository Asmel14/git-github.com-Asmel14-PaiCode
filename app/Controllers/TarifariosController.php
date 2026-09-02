<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/TarifariosModel.php';

class TarifariosController extends GenericModelController
{
    protected string $modelClass = 'TarifariosModel';
}
