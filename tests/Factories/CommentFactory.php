<?php

namespace Tests\Factories;

class CommentFactory
{
    public static function create(\PDO $pdo, $topicId, $userId, $body)
    {
        $sql = 'INSERT INTO berichten 
                    (topicid, userid, bericht, datum, tijd) 
                VALUES 
                    (?, ?, ?, NOW(), NOW())';
        $query = $pdo->prepare($sql);
        $query->execute([
            $topicId, $userId, $body
        ]);

        return $pdo->lastInsertId();
    }
}