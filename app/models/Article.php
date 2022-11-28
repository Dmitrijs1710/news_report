<?php

namespace App\models;

class Article
{
    private ?string $author;
    private ?string $title;
    private ?string $url;
    private ?string $urlToImage;
    private ?string $publishedAt;
    private ?string $description;

    public function __construct(
        ?string $author,
        ?string $title,
        ?string $url,
        ?string $publishedAt,
        ?string $description,
        ?string $urlToImage
    )
    {
        $this->author = $author;
        $this->title = $title;
        $this->url = $url;
        $this->urlToImage = $urlToImage;
        $this->publishedAt = $publishedAt;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getPublishedAt(): ?string
    {
        return $this->publishedAt;
    }

    /**
     * @return string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getUrlToImage(): ?string
    {
        return $this->urlToImage;
    }

}