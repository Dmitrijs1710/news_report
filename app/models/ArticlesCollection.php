<?php

namespace App\models;

class ArticlesCollection
{
    private array $articles;

    public function __construct(array $articles = [])
    {
        $this->articles = [];
        foreach ($articles as $article) {
            $this->add($article);
        }

    }

    public function add(Article $article)
    {
        $this->articles[] = $article;
    }

    /** @return Article[] */
    public function getAll(): array
    {
        return $this->articles;
    }
}