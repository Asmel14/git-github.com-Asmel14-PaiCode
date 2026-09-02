<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/DatosCentroEducativoModel.php';

class DatosCentroEducativoController extends GenericModelController
{
    protected string $modelClass = 'DatosCentroEducativoModel';
}
