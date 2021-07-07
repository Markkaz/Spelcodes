<?php


namespace Tests\Factories;


class NewsFactory
{
    public static function create(\PDO $pdo, $userId, $title, $body)
    {
        $sql = 'INSERT INTO nieuws 
                    (userid, titel, bericht, datum, tijd) 
                VALUES 
                    (?, ?, ?, NOW(), NOW());';
        $query = $pdo->prepare($sql);
        $query->execute([
            $userId,
            $title,
            $body
        ]);

        return $pdo->lastInsertId();
    }
}