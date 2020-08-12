<?php

namespace App\Model;
use Framework\Storage;

class User
{
    public static function all(): array
    {
        $storage = (new Storage())->getContents();
        return $storage;
    }

    public static function create(array $entry): void
    {
        (new Storage())
            ->addEntry($entry)
            ->save();
    }

    public static function delete(string $email): void
    {
        (new Storage)
            ->remove('email', $email)
            ->save();
    }
}