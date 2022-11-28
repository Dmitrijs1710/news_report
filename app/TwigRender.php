<?php

namespace App;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class TwigRender
{
    private string $link;
    private array $properties;

    public function __construct(string $link, array $properties)
    {
        $this->properties = $properties;
        $this->link = $link;
    }

    public function render(){
        $loader = new FilesystemLoader('views/');
        $twig = new Environment($loader, []);
        try {
            echo $twig->render($this->link, $this->properties);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            var_dump($e->getMessage());
        }
    }
}