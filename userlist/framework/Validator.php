<?php

namespace Framework;

/** Classe contendo funções de validação de dados. */
class Validator {
    /**
     * O campo tem algum valor não-vazio?
     * 
     * @param array $entry Array com os dados do formulario
     * @param string $field Nome do campo
     * 
     * @return bool
     */
    public static function exists(array $entry, string $field): bool
    {
        return isset($entry[$field]) && !empty($entry[$field]);
    }

    /**
     * O campo contém um e-mail válido?
     * 
     * @param array $entry Array com os dados do formulario
     * @param string $field Nome do campo
     * 
     * @return bool
     */
    public static function email(array $entry, string $field): bool
    {
        if (!$entry[$field]) {
            return true;
        }
        return filter_var($entry[$field], FILTER_VALIDATE_EMAIL);
    }
    
    /**
     * O campo contém um telefone válido? Considera DDD e telefones de 8 ou 9 posições. Esse regex
     * é bastante permissivo com o uso de hífen ou espaço para formatação do número.
     * 
     * @param array $entry Array com os dados do formulario
     * @param string $field Nome do campo
     * 
     * @return bool
     */
    public static function phone(array $entry, string $field): bool
    {
        if (!$entry[$field]) {
            return true;
        }
        $phone = str_replace(' ', '', str_replace('-', '', $entry[$field]));
        return !!preg_match('/^(\(\d{2}\))?\d{8,9}$/', $phone);
    }

    /**
     * O campo contém um valor único? Usa a classe Storage para verificar se já existe um registro do campo "field" com
     * esse valor
     * 
     * @param array $entry Array com os dados do formulario
     * @param string $field Nome do campo
     * 
     * @return bool
     */
    public static function unique(array $entry, string $field): bool
    {
        if (!$entry[$field]) {
            return true;
        }
        $storage = (new Storage())->getContents();
        // Extrai a coluna que queremos que seja única:
        $column = array_column($storage, $field);
        return !in_array($entry[$field], $column);
    }
}