<?php

namespace App\ViewVariables;

use App\Services\CategoryNavigationService;

class MenuVariables
{
    public function getName() :string
    {
        return 'categories';
    }
    public function getValues() :array
    {
        return (new CategoryNavigationService())->getCategoryMenu();
    }
}