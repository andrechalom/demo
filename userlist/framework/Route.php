<?php

namespace Framework;

class Route {
    private string $method;
    private string $uri;
    private string $action;
    public function __construct(string $method, string $uri, string $action)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->action = $action;
    }
}