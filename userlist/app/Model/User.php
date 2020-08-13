<?php

namespace App\Model;
use Framework\Storage;

class User
{
    private static Storage $storage;

    private static function initStorage(): void
    {
        if (!isset(self::$storage)) {
            self::$storage = new Storage('users');
        }
    }

    /** Retorna a lista de todos os usuários existentes */
    public static function all(): array
    {
        self::initStorage();
        return self::$storage->getContents();
    }

    /** Cria um novo registro de usuário a partir do array $entry */
    public static function create(array $entry): void
    {
        self::initStorage();
        self::$storage->addEntry($entry);
    }

    /** Remove o registro de usuário com o email indicado */
    public static function delete(string $email): void
    {
        self::initStorage();
        self::$storage->remove('email', $email);
    }

    /** Persiste as alterações feitas em disco */
    public static function commit(): void
    {
        self::initStorage();
        self::$storage->save();
    }
}