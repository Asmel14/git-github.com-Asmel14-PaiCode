<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/InscripcionesModel.php';

class InscripcionesController extends GenericModelController
{
    protected string $modelClass = 'InscripcionesModel';
}
