<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/DiscapacidadesModel.php';

class DiscapacidadesController extends GenericModelController
{
    protected string $modelClass = 'DiscapacidadesModel';
}
