<?php

require_once 'vendor/autoload.php';

use App\Template;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$loader = new FilesystemLoader('views/');
$twig = new Environment($loader, []);

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    //article
    $r->addRoute('GET', '/', ['\App\Controllers\ArticleController', 'index']);
    $r->addRoute('GET', '/country={country}', ['\App\Controllers\ArticleController', 'index']);
    $r->addRoute('GET', '/article={title}', ['\App\Controllers\ArticleController', 'index']);
    $r->addRoute('GET', '/country={country}/article={title}', ['\App\Controllers\ArticleController', 'index']);
    //category
    $r->addRoute('GET', '/country={country}/category={name}', ['\App\Controllers\CategoryController', 'index']);
    $r->addRoute('GET', '/category={name}', ['\App\Controllers\CategoryController', 'index']);
    $r->addRoute('GET', '/category={name}/article={title}', ['\App\Controllers\CategoryController', 'index']);
    $r->addRoute('GET', '/country={country}/category={name}/article={title}', ['\App\Controllers\CategoryController', 'index']);
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        [$controller, $method] = $handler;
        $response = (new $controller)->{$method}($vars);
        if ($response instanceof Template) {
            try {
                echo $twig->render($response->getLink(), $response->getProperties());
            } catch (LoaderError|RuntimeError|SyntaxError $e) {
                echo($e->getMessage());
            }
        }
        break;
}
