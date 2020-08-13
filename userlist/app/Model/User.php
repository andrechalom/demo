<?php

namespace App\Model;
use Framework\Interfaces\Storage;

class User
{
    private static Storage $storage;

    public static function initStorage(Storage $storage): void
    {
        self::$storage = $storage;
    }

    /** Retorna a lista de todos os usuários existentes */
    public static function all(): array
    {
        return self::$storage->getContents();
    }

    /** Cria um novo registro de usuário a partir do array $entry */
    public static function create(array $entry): void
    {
        self::$storage->addEntry($entry);
    }

    /** Remove o registro de usuário com o email indicado */
    public static function delete(string $email): void
    {
        self::$storage->remove('email', $email);
    }

    /** Persiste as alterações feitas em disco */
    public static function commit(): void
    {
        self::$storage->save();
    }
}