<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/AniosEscolaresModel.php';

class AniosEscolaresController extends GenericModelController
{
    protected string $modelClass = 'AniosEscolaresModel';
}
