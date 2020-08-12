<?php

namespace App;

use Framework\Request;
use Framework\Response;
use Framework\Router;
use Framework\Exception\ApplicationException;

class App
{
    static private $controllerNamespace = "App\\Controller\\";
    public static function run(string $method, string $uri, array $request): void
    {
        try {
            $request = new Request();
            $route = Router::match($method, $uri);
            $controllerClass = self::$controllerNamespace . $route->controller();
            $controller = new $controllerClass();
            $action = $route->action();
            $identifier = $route->extractIdentifier($uri);
            $response = $controller->{$action}($request, $identifier);
        } catch (ApplicationException $e) {
            $response = new Response(400, [$e->getMessage()]);
        } catch (\Throwable $e) {
            $response = new Response(500, [$e->getMessage()]);
        }
        $response->render();
    }
}