<?php

namespace App\Controllers;

use App\models\Article;
use App\models\ArticlesCollection;
use jcobhams\NewsApi\NewsApi;
use jcobhams\NewsApi\NewsApiException;


class ArticleController
{

    public function index(array $vars) :array
    {
        $title=$vars['title']??'';
        $newsApi = new NewsApi($_ENV['NEWS_API_KEY']);
        try {
            if($title === ''){
                $headlines = $newsApi->getTopHeadLines(null, null, 'lv');
            } else {
                $headlines = $newsApi->getTopHeadLines($title);
            }
        } catch (NewsApiException $e) {
            $headlines = false;
        }
        $articles = new ArticlesCollection();
        if ($headlines !== false) {
            foreach ($headlines->articles as $article) {
                $articles->add(new Article(
                    $article->author,
                    $article->title,
                    $article->url,
                    $article->publishedAt,
                    $article->description,
                    $article->urlToImage
                ));
            }
        }


        return ['Article/index.html', [
            'articles' => $articles->getAll(),
            'categories' => $newsApi->getCategories()
        ]];

    }
}