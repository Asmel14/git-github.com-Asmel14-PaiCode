<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/NominaDetalleConceptosModel.php';

class NominaDetalleConceptosController extends GenericModelController
{
    protected string $modelClass = 'NominaDetalleConceptosModel';
}
