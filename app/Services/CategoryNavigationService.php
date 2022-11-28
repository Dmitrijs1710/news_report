<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Category;
use App\Models\Collections\ArticlesCollection;
use jcobhams\NewsApi\NewsApi;
use jcobhams\NewsApi\NewsApiException;

class CategoryNavigationService
{
    public function execute(string $title, string $categoryTitle): Category
    {

        $newsApi = new NewsApi($_ENV['NEWS_API_KEY']);
        try {
            if (in_array($categoryTitle, $newsApi->getCategories())) {
                $headlines = $newsApi->getTopHeadLines($title === '' ? null : $title, null, null, $categoryTitle);

            } else {
                if ($title !== '') {
                    $headlines = $newsApi->getEverything($title);
                } else {
                    $headlines = $newsApi->getEverything($categoryTitle);
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
        return ($category);
    }

    public function getMenu(): array
    {
        $newsApi = new NewsApi($_ENV['NEWS_API_KEY']);
        return $newsApi->getCategories();
    }
}