<?php

namespace Framework;

class Route {
    private string $method;
    private string $uri;
    private string $controller;
    private string $action;

    /**
     * Cria uma nova rota. Para uma interface padronizada, use sempre Router::register!
     * 
     * @param string $method Método HTTP ('GET', 'POST', 'PUT' ou 'DELETE')
     * @param string $uri URI ou expressão regular a ser comparada com o request. Use um grupo na expressão regular
     * para capturar um identificador (ex: "users/(\w*)").
     * @param string $controller Nome do controller a ser chamado. Deve ser o nome base da classe, não contendo namespace;
     * @param string $action Nome do método a ser executado no controller. 
     * 
     */
    public function __construct(string $method, string $uri, string $controller, string $action)
    {
        $this->method = strtoupper($method);
        $this->controller = $controller;
        $this->action = $action;
        // Normaliza a URI para iniciar com /
        $uri = substr($uri, 0, 1) !== '/' ? '/' . $uri : $uri;
        $uri = str_replace('/', '\/', $uri);
        $this->uri = '/^' . $uri . '$/';
    }

    /**
     * Valida se uma combinação de método e URI são compatíveis com esta rota. A compatibilidade
     * de URI é feita usando expressões regulares.
     * 
     * @param string $method Método HTTP ('GET', 'POST', 'PUT' ou 'DELETE')
     * @param string $uri URI recebida no request
     * 
     * @return bool
     */
    public function matches(string $method, string $uri): bool {
        if (strtoupper($method) !== $this->method) {
            return false;
        }
        if (preg_match($this->uri, $uri)) {
            return true;
        }
        return false;
    }

    /**
     * Extrai um identificador da rota. Caso a rota tenha sido definida com uma expressão
     * regular, retorna o primeiro grupo de expressão regular que foi encontrado.
     * Ex: se a rota é "users/(\w+)" e a URI é "users/john", o identificador retornado é "john".
     * 
     * @param string $uri URI recebida no request
     */
    public function extractIdentifier(string $uri): ?string
    {
        if (preg_match($this->uri, $uri, $parameters)) {
            if (count($parameters) > 1) {
                return $parameters[1];
            }
        }
        return null;
    }

    /** Retorna o nome da classe de controller registrado para essa rota */
    public function controller(): string {
        return $this->controller;
    }

    /** Retorna o nome do método registrado para essa rota */
    public function action(): string {
        return $this->action;
    }
}