<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/DocumentosModel.php';

class DocumentosController extends GenericModelController
{
    protected string $modelClass = 'DocumentosModel';
}
