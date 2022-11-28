<?php

namespace App\Controllers;

use App\Services\CategoryNavigationService;
use App\Services\IndexArticleService;
use App\Template;


class ArticleController
{

    public function index(): Template
    {
        $title = $_GET['search'] ?? '';

        $articles = (new IndexArticleService())->execute($title);
        $menu = (new CategoryNavigationService())->getMenu();

        return new Template('Article/index.html', [
            'articles' => $articles->getAll(),
            'categories' => $menu
        ]);

    }
}