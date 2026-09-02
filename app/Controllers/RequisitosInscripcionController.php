<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/RequisitosInscripcionModel.php';

class RequisitosInscripcionController extends GenericModelController
{
    protected string $modelClass = 'RequisitosInscripcionModel';
}
