<?php

namespace Framework;

class Response
{
    private int $status;
    private array $data;

    public function __construct(int $status, array $data)
    {
        $this->status = $status;
        $this->data = $data;
    }

    public function render(): void
    {
        http_response_code($this->status);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($this->data, JSON_UNESCAPED_UNICODE);
    }
}