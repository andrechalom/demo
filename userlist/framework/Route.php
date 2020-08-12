<?php

namespace Framework;

class Route {
    private string $method;
    private string $uri;
    private string $controller;
    private string $action;

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

    public function matches(string $method, string $uri): bool {
        if (strtoupper($method) !== $this->method) {
            return false;
        }
        if (preg_match($this->uri, $uri)) {
            return true;
        }
        return false;
    }

    public function extractIdentifier(string $uri): ?string {
        if (preg_match($this->uri, $uri, $parameters)) {
            if (count($parameters) > 1) {
                return $parameters[1];
            }
        }
        return null;
    }

    public function controller(): string {
        return $this->controller;
    }

    public function action(): string {
        return $this->action;
    }
}