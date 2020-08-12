<?php

namespace Framework;

use Framework\Exception\NotFoundException;

/** Abstração simples para um sistema de arquivo */
class Storage {
    private static $filename = "./users.txt";
    private array $data = [];

    /**
     * Cria um novo objeto da classe Storage, contendo os dados lidos a partir do disco.
     */
    public function __construct() {
        if (!file_exists(self::$filename)) {
            return;
        }
        $fileContents = file_get_contents(self::$filename);
        $this->data = json_decode($fileContents, true);
    }

    /**
     * Retorna um array associativo contendo todos os dados já salvos
     */
    public function getContents(): array {
        return $this->data;
    }

    /**
     * Inclui um novo registro em memória, use "save" para persistir os dados em disco
     * 
     * @param array $entry Array associativo contendo os dados a serem salvos
     */
    public function addEntry(array $entry): self
    {
        $this->data[] = $entry;
        return $this;
    }

    public function remove(string $field, string $content): self
    {
        foreach($this->data as $key => $item) {
            if ($item[$field] === $content) {
                unset($this->data[$key]);
                return $this;
            }
        }
        throw new NotFoundException("Resource with $field equal to $content was not found!");
    }

    /**
     * Salva em disco o conteúdo desse storage.
     */
    public function save(): void
    {
        $file = fopen(self::$filename, "w");
        fwrite($file, json_encode($this->data));
    }
}