<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/AuditoriaModel.php';

class AuditoriaController extends GenericModelController
{
    protected string $modelClass = 'AuditoriaModel';
}
