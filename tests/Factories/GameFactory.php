<?php

namespace Tests\Factories;

class GameFactory
{
    public static function create(\PDO $pdo, $consoleId, $name, $directory, $developer, $publisher, $developerUrl, $publisherUrl)
    {
        $sql = 'INSERT INTO spellen 
                    (consoleid, naam, map, developer, publisher, developerurl, publisherurl, rating, stemmen) 
                VALUES 
                    (?, ?, ?, ?, ?, ?, ?, 0, 0)';
        $query = $pdo->prepare($sql);
        $query->execute([
            $consoleId,
            $name,
            $directory,
            $developer,
            $publisher,
            $developerUrl,
            $publisherUrl
        ]);

        return $pdo->lastInsertId();
    }

    public static function highlight(\PDO $pdo, $consoleId, $gameId)
    {
        $sql = 'INSERT INTO spellenview (consoleid, spelid) VALUES (?, ?);';
        $query = $pdo->prepare($sql);
        $query->execute([$consoleId, $gameId]);
    }
}