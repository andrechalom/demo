<?php

namespace App;

use Framework\Request;
use Framework\Router;

class App
{
    static private $controllerNamespace = "App\\Controller\\";
    public static function run(string $method, string $uri, array $request): void
    {
        $request = new Request($request);
        $route = Router::match($method, $uri);
        $controllerClass = self::$controllerNamespace . $route->controller();
        $controller = new $controllerClass();
        $action = $route->action();
        $identifier = $route->extractIdentifier($uri);
        $response = $controller->{$action}($request, $identifier);
        var_dump($controller);
        var_dump($action);
        echo "OI";
    }
}