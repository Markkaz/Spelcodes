<?php

namespace Tests\Factories;

class ConsoleFactory
{
    public static function create(\PDO $pdo, $name)
    {
        $sql = 'INSERT INTO consoles (naam) VALUES (?)';
        $query = $pdo->prepare($sql);
        $query->execute([$name]);

        return $pdo->lastInsertId();
    }
}