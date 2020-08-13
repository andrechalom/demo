<?php

namespace App;

use Framework\Request;
use Framework\Response;
use Framework\Router;
use Framework\Exception\ApplicationException;
use Framework\FileStorage;

class App
{
    static private $controllerNamespace = "App\\Controller\\";
    
    /**
     * Injeta o storage para a classe Users
     */
    private static function bootstrapUsers() {
        \App\Model\User::initStorage(new FileStorage('users'));
    }

    /**
     * Executa o ciclo completo da aplicação, lendo o request, direcionando o fluxo
     * para um Controller e renderizando a resposta para o cliente.
     * 
     * @param string $method Método HTTP recebido (GET, POST, etc)
     * @param string $uri URI recebida pelo servidor.
     */
    public static function run(string $method, string $uri): void
    {
        try {
            self::bootstrapUsers();
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