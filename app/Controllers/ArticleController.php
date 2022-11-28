<?php

namespace App\Controllers;

use App\models\Article;
use App\models\ArticlesCollection;
use jcobhams\NewsApi\NewsApi;
use jcobhams\NewsApi\NewsApiException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class ArticleController
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function index(array $vars)
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

        $loader = new FilesystemLoader('views/');
        $twig = new Environment($loader, []);
        echo $twig->render('Article/index.html', [
            'articles' => $articles->getAll(),
            'categories' => $newsApi->getCategories()
        ]);
    }
}