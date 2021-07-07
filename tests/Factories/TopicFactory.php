<?php

namespace Tests\Factories;

class TopicFactory
{
    public static function create(\PDO $pdo, $userId, $title, $content)
    {
        $sql = 'INSERT INTO topics
                    (userid, titel, bericht, datum, tijd) 
                VALUES 
                    (?, ?, ?, NOW(), NOW());';
        $query = $pdo->prepare($sql);
        $query->execute([
            $userId,
            $title,
            $content
        ]);

        return $pdo->lastInsertId();
    }

    public static function attach(\PDO $pdo, $topicId, $gameId)
    {
        $sql = 'INSERT INTO spellenhulp (spelid, topicid) VALUES (?, ?);';
        $query = $pdo->prepare($sql);
        $query->execute([
            $gameId, $topicId
        ]);
    }
}