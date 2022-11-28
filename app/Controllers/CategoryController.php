<?php

namespace App\Controllers;

use App\Services\CategoryNavigationService;
use App\Template;
use App\TwigRender;


class CategoryController
{
    public function index(array $vars): Template
    {
        $categoryTitle = $vars['name'] ?? '';
        $title = $_GET['search'] ?? '';

        $category = (new CategoryNavigationService())->execute($title, $categoryTitle);
        $menu = (new CategoryNavigationService())->getMenu();

        return new Template('Category/index.html', [
            'articles' => $category->getArticles()->getAll(),
            'categories' => $menu,
            'currentCategory' => $category->getName()
        ]);
    }
}