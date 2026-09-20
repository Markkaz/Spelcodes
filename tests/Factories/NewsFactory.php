<?php


namespace Tests\Factories;


class NewsFactory
{
    public static function create(\PDO $pdo, $userId, $title, $body, \DateTime $publishedAt = null)
    {
        $publishedAt = $publishedAt ?: new \DateTime();

        $sql = 'INSERT INTO nieuws 
                (userid, titel, bericht, datum, tijd) 
            VALUES 
                (?, ?, ?, ?, ?);';
        $query = $pdo->prepare($sql);
        $query->execute([
            $userId,
            $title,
            $body,
            $publishedAt->format('Y-m-d'),
            $publishedAt->format('H:i:s')
        ]);

        return $pdo->lastInsertId();
    }
}