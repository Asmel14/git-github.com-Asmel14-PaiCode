<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/EstudianteFamiliaresModel.php';

class EstudianteFamiliaresController extends GenericModelController
{
    protected string $modelClass = 'EstudianteFamiliaresModel';
}
