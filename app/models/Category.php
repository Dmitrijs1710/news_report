<?php

namespace App\models;

class Category
{
    private ArticlesCollection $articles;
    private string $name;

    public function __construct(string $name, ArticlesCollection $articles)
    {
        $this->name = $name;
        $this->articles = $articles;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getArticles(): ArticlesCollection
    {
        return $this->articles;
    }

    public function addArticle(Article $article) :void
    {
        $this->articles->add($article);
    }
}