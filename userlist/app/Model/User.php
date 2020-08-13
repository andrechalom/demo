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

    public static function all(): array
    {
        self::initStorage();
        return self::$storage->getContents();
    }

    public static function create(array $entry): void
    {
        self::initStorage();
        self::$storage->addEntry($entry);
    }

    public static function delete(string $email): void
    {
        self::initStorage();
        self::$storage->remove('email', $email);
    }

    public static function commit(): void
    {
        self::initStorage();
        self::$storage->save();
    }
}