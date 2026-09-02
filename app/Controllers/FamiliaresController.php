<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/FamiliaresModel.php';

class FamiliaresController extends GenericModelController
{
    protected string $modelClass = 'FamiliaresModel';
}
