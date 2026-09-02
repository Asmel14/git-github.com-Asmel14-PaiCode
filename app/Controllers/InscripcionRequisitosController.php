<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/InscripcionRequisitosModel.php';

class InscripcionRequisitosController extends GenericModelController
{
    protected string $modelClass = 'InscripcionRequisitosModel';
}
