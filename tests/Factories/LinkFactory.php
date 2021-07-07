<?php

namespace Tests\Factories;

class LinkFactory
{
    public static function create(\PDO $pdo, $name, $url)
    {
        $sql = 'INSERT INTO links 
                    (link, url, incomming, outcomming) 
                VALUES 
                    (?, ?, 0, 0)';
        $query = $pdo->prepare($sql);
        $query->execute([
            $name, $url
        ]);

        return $pdo->lastInsertId();
    }
}