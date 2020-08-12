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
        echo "BLOB";
        echo $this->status;
        echo json_encode($this->data);
    }
}