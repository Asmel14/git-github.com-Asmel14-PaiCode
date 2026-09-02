<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/CajaSesionesModel.php';

class CajaSesionesController extends GenericModelController
{
    protected string $modelClass = 'CajaSesionesModel';
}
