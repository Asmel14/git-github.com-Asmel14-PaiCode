<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/ConceptosNominaModel.php';

class ConceptosNominaController extends GenericModelController
{
    protected string $modelClass = 'ConceptosNominaModel';
}
