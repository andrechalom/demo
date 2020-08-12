<?php

namespace App;

use Framework\Request;
use Framework\Router;

class App
{
    public static function run(string $method, string $uri, array $request): void
    {
        $request = new Request($request);
        $route = Router::match($method, $uri);
        $identifier = $route->extractIdentifier($uri);
        $action = $route->action();
        var_dump($identifier);
        var_dump($action);
        echo "OI";
    }
}