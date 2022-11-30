<?php

namespace App\Controllers;

use App\Services\CategoryNavigationService;
use App\Services\IndexArticleService;
use App\Template;


class ArticleController
{

    public function index(array $vars): Template
    {
        $title = $_GET['search'] ?? null;
        $country = $vars['country'] ?? null;

        $articles = (new IndexArticleService())->execute($title, $country);
        $menu = (new CategoryNavigationService())->getMenu();

        return new Template('Article/index.html', [
            'articles' => $articles->getAll(),
            'categories' => $menu,
            'country' => $country,
            'placeholder' => $title
        ]);

    }
}