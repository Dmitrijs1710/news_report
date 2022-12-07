<?php

namespace App\Controllers;

use App\Services\IndexArticleService;
use App\Template;


class ArticleController
{

    public function index(array $vars): Template
    {
        $title = ($_GET['search'] ?? $vars['title']);
        $country = $vars['country'] ?? null;


        $articles = (new IndexArticleService())->execute($title, $country);
        return new Template('Article/index.html', [
            'articles' => $articles->getAll(),
            'country' => $country,
            'placeholder' => $title,
        ]);

    }
}