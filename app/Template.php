<?php

namespace App;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class Template
{
    private string $link;
    private array $properties;

    public function __construct(string $link, array $properties)
    {
        $this->properties = $properties;
        $this->link = $link;
    }

    public function render() :string
    {
        $loader = new FilesystemLoader('views/');
        $twig = new Environment($loader, []);
        try {
            return $twig->render($this->link, $this->properties);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            return ($e->getMessage());
        }
    }

    public function getLink(): string
    {
        return $this->link;
    }


    public function getProperties(): array
    {
        return $this->properties;
    }
}