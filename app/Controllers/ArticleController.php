<?php

namespace App\Controllers;

use App\Services\CategoryNavigationService;
use App\Services\IndexArticleService;
use App\Services\UserInformationGetterService;
use App\Template;


class ArticleController
{

    public function index(array $vars): Template
    {
        $title = ($_GET['search'] ?? $vars['title']);
        $country = $vars['country'] ?? null;


        $articles = (new IndexArticleService())->execute($title, $country);
        $menu = (new CategoryNavigationService())->getCategoryMenu();
        return new Template('Article/index.html', [
            'articles' => $articles->getAll(),
            'categories' => $menu,
            'country' => $country,
            'placeholder' => $title,
            'login' => $_SESSION['id'] !== null ? (new UserInformationGetterService())->execute($_SESSION['id']) : null
        ]);

    }
}