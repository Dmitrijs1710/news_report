<?php

namespace App\Models\Collections;

use App\Models\Article;

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

    public function add(Article $article): void
    {
        $this->articles[] = $article;
    }

    /** @return Article[] */
    public function getAll(): array
    {
        return $this->articles;
    }
}