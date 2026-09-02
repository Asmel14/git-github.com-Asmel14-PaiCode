<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/RegistrosCivilesModel.php';

class RegistrosCivilesController extends GenericModelController
{
    protected string $modelClass = 'RegistrosCivilesModel';
}
