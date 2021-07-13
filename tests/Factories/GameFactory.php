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

    public static function vote(\PDO $pdo, $gameId, $vote, $ip)
    {
        $sql = 'INSERT INTO stemmen (spelid, ip) VALUES (?, ?);';
        $query = $pdo->prepare($sql);
        $query->execute([$gameId, $ip]);

        $sql = 'UPDATE spellen SET rating=rating + ?, stemmen=stemmen+1 WHERE spelid=?;';
        $query = $pdo->prepare($sql);
        $query->execute([$vote, $gameId]);
    }
}