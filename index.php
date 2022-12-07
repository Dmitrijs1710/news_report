<?php

require_once 'vendor/autoload.php';

use App\Redirect;
use App\Template;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

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
    $r->addRoute('GET', '/login', ['\App\Controllers\UserLoginController', 'index']);
    $r->addRoute('POST', '/login', ['\App\Controllers\UserLoginController', 'loginHandler']);
    $r->addRoute('GET', '/registration', ['\App\Controllers\RegistrationController', 'index']);
    $r->addRoute('POST', '/registration', ['\App\Controllers\RegistrationController', 'registrationHandler']);
    $r->addRoute('GET', '/logout', ['\App\Controllers\UserLoginController', 'logoutHandler']);
    $r->addRoute('GET', '/profile', ['\App\Controllers\ProfileController', 'index']);
    $r->addRoute('POST', '/profile', ['\App\Controllers\ProfileController', 'updateData']);
    $r->addRoute('GET', '/registration/successful', ['\App\Controllers\RegistrationController', 'registeredHandler']);
    $r->addRoute('GET', '/login/successful', ['\App\Controllers\UserLoginController', 'successful']);

});
$loader = new FilesystemLoader('views/');
$twig = new Environment($loader, []);

$localVariables = [
    \App\ViewVariables\LoginVariables::class,
    \App\ViewVariables\ErrorVariables::class,
    \App\ViewVariables\MenuVariables::class
];

foreach ($localVariables as $variable) {
    $variable = new $variable;
    $twig->addGlobal($variable->getName(), $variable->getValues());
}

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
                unset($_SESSION['error']);
            } catch (LoaderError|RuntimeError|SyntaxError $e) {
                echo($e->getMessage());
            }
        }
        if ($response instanceof Redirect) {
            header('Location: ' . $response->getLink());
        }
        break;
}
