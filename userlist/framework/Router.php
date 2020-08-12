<?php

namespace Framework;

use Framework\Exception\RouteException;

class Router
{
    private static $routes = [];
    public static function register(string $method, string $uri, string $controller, string $action)
    {
        if (!in_array($method, ['GET', 'POST', 'PUT', 'DELETE'])) {
            throw new RouteException("Request method does not match available methods");
        }
        self::$routes[] = new Route($method, $uri, $controller, $action);
    }

    public static function match(string $method, string $uri): Route
    {
        if (!in_array($method, ['GET', 'POST', 'PUT', 'DELETE'])) {
            throw new RouteException("Request method does not match available methods");
        }
        foreach (self::$routes as $route) {
            if ($route->matches($method, $uri)) {
                return $route;
            }
        }
        throw new RouteException("Request does not match any route");
    }
}