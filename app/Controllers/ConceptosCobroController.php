<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/ConceptosCobroModel.php';

class ConceptosCobroController extends GenericModelController
{
    protected string $modelClass = 'ConceptosCobroModel';
}
