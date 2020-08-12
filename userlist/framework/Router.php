<?php

namespace Framework;

class Router
{
    private static $routes = [];
    public static function register(string $method, string $uri, string $action)
    {
        self::$routes[] = new Route($method, $uri, $action);
    }
}