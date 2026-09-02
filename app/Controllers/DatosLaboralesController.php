<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/DatosLaboralesModel.php';

class DatosLaboralesController extends GenericModelController
{
    protected string $modelClass = 'DatosLaboralesModel';
}
