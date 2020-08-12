<?php

namespace Framework;

class Request
{
    private array $data = [];
    public function __construct(array $data)
    {
        $this->data = $data;
    }
}