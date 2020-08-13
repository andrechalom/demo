<?php

namespace Framework;

use Framework\Exception\RouteException;

/** Classe responsável por detectar qual controller deve ser executado a partir de cada request */
class Router
{
    private static $routes = [];

    /**
     * Registra uma rota, ou seja, associa um método e padrão de URI a uma função a ser executada em um controller
     * 
     * @param string $method Método HTTP ('GET', 'POST', 'PUT' ou 'DELETE')
     * @param string $uri URI ou expressão regular a ser comparada com o request. Use um grupo na expressão regular
     * para capturar um identificador (ex: "users/(\w*)").
     * @param string $controller Nome do controller a ser chamado. Deve ser o nome base da classe, não contendo namespace;
     * @param string $action Nome do método a ser executado no controller. 
     * 
     */
    public static function register(string $method, string $uri, string $controller, string $action): void
    {
        if (!in_array($method, ['GET', 'POST', 'PUT', 'DELETE'])) {
            throw new RouteException("Request method does not match available methods");
        }
        self::$routes[] = new Route($method, $uri, $controller, $action);
    }

    /**
     * Percorre a lista de rota registradas e retorna a primeira compatível com o método e URI recebidos
     * 
     * @param string $method Método HTTP ('GET', 'POST', 'PUT' ou 'DELETE')
     * @param string $uri URI recebida no request
     * 
     * @return Route
     */
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