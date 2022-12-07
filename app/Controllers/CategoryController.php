<?php

namespace App\Controllers;

use App\Services\CategoryNavigationService;
use App\Template;


class CategoryController
{
    public function index(array $vars): Template
    {
        $categoryTitle = $vars['name'] ?? null;
        $title = $_GET['search'] ?? null;
        $country = $vars['country'] ?? null;
        $category = (new CategoryNavigationService())->execute($title, $categoryTitle, $country);
        return new Template('Category/index.html', [
            'articles' => $category->getArticles()->getAll(),
            'currentCategory' => $category->getName(),
            'country' => $country,
            'placeholder' => $title,
        ]);
    }
}