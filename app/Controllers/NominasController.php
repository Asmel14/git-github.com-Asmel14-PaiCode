<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/NominasModel.php';

class NominasController extends GenericModelController
{
    protected string $modelClass = 'NominasModel';
}
