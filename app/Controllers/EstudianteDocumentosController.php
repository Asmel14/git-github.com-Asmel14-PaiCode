<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/EstudianteDocumentosModel.php';

class EstudianteDocumentosController extends GenericModelController
{
    protected string $modelClass = 'EstudianteDocumentosModel';
}
