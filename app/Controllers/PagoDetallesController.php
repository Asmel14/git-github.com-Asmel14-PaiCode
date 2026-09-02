<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/PagoDetallesModel.php';

class PagoDetallesController extends GenericModelController
{
    protected string $modelClass = 'PagoDetallesModel';
}
