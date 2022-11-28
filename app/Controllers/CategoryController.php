<?php

namespace App\Controllers;

use App\models\Article;
use App\models\ArticlesCollection;
use App\models\Category;
use jcobhams\NewsApi\NewsApi;
use jcobhams\NewsApi\NewsApiException;

class CategoryController
{
    public function index(array $vars): array
    {
        $categoryTitle = $vars['name'] ?? '';
        $title = $vars['title'] ?? '';
        $newsApi = new NewsApi($_ENV['NEWS_API_KEY']);
        try {
            if (in_array($categoryTitle, $newsApi->getCategories())) {
                $headlines = $newsApi->getTopHeadLines($title === '' ? null : $title, null, null, $categoryTitle);

            } else {
                if ($title !== '') {
                    $headlines = $newsApi->getEverything($title, null, null);
                } else {
                    $headlines = $newsApi->getEverything($categoryTitle, null, null);
                }
            }

        } catch (NewsApiException $e) {
            $headlines = false;
        }
        $category = new Category($categoryTitle, new ArticlesCollection());
        if ($headlines !== false && in_array($categoryTitle, $newsApi->getCategories())) {
            foreach ($headlines->articles as $article) {
                $category->addArticle(new Article(
                    $article->author,
                    $article->title,
                    $article->url,
                    $article->publishedAt,
                    $article->description,
                    $article->urlToImage
                ));
            }
        }
        return(['Category/index.html',[
            'articles' => $category->getArticles()->getAll(),
            'categories' => $newsApi->getCategories(),
            'currentCategory' => $category->getName()
        ]]);
    }
}