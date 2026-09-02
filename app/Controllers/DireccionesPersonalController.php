<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/DireccionesPersonalModel.php';

class DireccionesPersonalController extends GenericModelController
{
    protected string $modelClass = 'DireccionesPersonalModel';
}
