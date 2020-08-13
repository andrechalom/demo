<?php

namespace Framework;

use Framework\Exception\NotFoundException;

/** Abstração simples para um sistema de arquivo */
class FileStorage implements \Framework\Interfaces\Storage {
    private string $filename;
    private array $data = [];

    /**
     * Cria um novo objeto da classe Storage, contendo os dados lidos a partir do disco.
     * 
     * @param string $modelname O nome do model, models com nomes diferentes serão armazenados
     * em arquivos diferentes.
     */
    public function __construct(string $modelname) {
        $this->filename = $modelname . ".txt";
        if (!file_exists($this->filename)) {
            return;
        }
        $fileContents = file_get_contents($this->filename);
        $this->data = json_decode($fileContents, true, 512, JSON_UNESCAPED_UNICODE);
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

    /**
     * Remove a primeira entrada cujo "field" seja igual ao "content".
     * 
     * @param string $field O campo a ser selecionado
     * @param string $content O valor que está sendo buscado
     */
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
        $file = fopen($this->filename, "w");
        fwrite($file, json_encode($this->data, JSON_UNESCAPED_UNICODE));
    }
}