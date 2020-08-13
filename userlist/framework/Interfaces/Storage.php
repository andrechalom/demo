<?php

namespace Framework\Interfaces;

interface Storage {
    /**
     * Cria um novo objeto da classe Storage, contendo os dados lidos a partir do disco.
     * 
     * @param string $modelname O nome do model, models com nomes diferentes serão armazenados
     * em arquivos diferentes.
     */
    public function __construct(string $modelname);

    /**
     * Retorna um array associativo contendo todos os dados já salvos
     */
    public function getContents(): array;

    /**
     * Inclui um novo registro em memória, use "save" para persistir os dados em disco
     * 
     * @param array $entry Array associativo contendo os dados a serem salvos
     */
    public function addEntry(array $entry): self;

    /**
     * Remove a primeira entrada cujo "field" seja igual ao "content".
     * 
     * @param string $field O campo a ser selecionado
     * @param string $content O valor que está sendo buscado
     */
    public function remove(string $field, string $content): self;

    /**
     * Persiste em disco o conteúdo desse storage.
     */
    public function save(): void;
};