<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/PersonalDocumentosModel.php';

class PersonalDocumentosController extends GenericModelController
{
    protected string $modelClass = 'PersonalDocumentosModel';
}
