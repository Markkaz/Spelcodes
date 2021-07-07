<?php

namespace Tests\Factories;

class NewsCommentFactory
{
    public static function create(\PDO $pdo, $nieuwsId, $userId, $body)
    {
        $sql = 'INSERT INTO nieuwsreacties 
                    (nieuwsid, userid, bericht, datum, tijd) 
                VALUES 
                    (?, ?, ?, NOW(), NOW());';
        $query = $pdo->prepare($sql);
        $query->execute([
            $nieuwsId,
            $userId,
            $body
        ]);

        return $pdo->lastInsertId();
    }
}