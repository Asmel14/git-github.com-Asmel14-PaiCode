<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/EstudiosConcluidosModel.php';

class EstudiosConcluidosController extends GenericModelController
{
    protected string $modelClass = 'EstudiosConcluidosModel';
}
