<?php

declare(strict_types=1);

require_once __DIR__ . '/GenericModelController.php';
require_once __DIR__ . '/../Models/PersonalModel.php';

class PersonalController extends GenericModelController
{
    protected string $modelClass = 'PersonalModel';
}
