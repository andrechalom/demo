<?php

namespace Framework;

class Response
{
    private int $status;
    private array $data;

    /** 
     * Cria uma nova resposta API a ser enviada para o cliente
     * 
     * @param int $status Código HTTP a ser retornado. Ex: 200 indica sucesso, 500 indica erro
     * @param array $data Dados a ser retornados para o cliente.
     */
    public function __construct(int $status, array $data)
    {
        $this->status = $status;
        $this->data = $data;
    }

    /**
     * Envia a resposta ao cliente.
     */
    public function render(): void
    {
        http_response_code($this->status);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($this->data, JSON_UNESCAPED_UNICODE);
    }
}