<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Collections\ArticlesCollection;
use jcobhams\NewsApi\NewsApi;
use jcobhams\NewsApi\NewsApiException;

class IndexArticleService
{

    public function execute(?string $title, ?string $country): ArticlesCollection
    {

        $newsApi = new NewsApi($_ENV['NEWS_API_KEY']);
        try {
            if ($country !== null) {
                $headlines = $newsApi->getTopHeadLines($title ?? null, null, $country ?? null);

            } else {
                $headlines = $newsApi->getEverything($title ?? 'a', null, null, null, '2022-11-16');
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
        return $articles;
    }
}