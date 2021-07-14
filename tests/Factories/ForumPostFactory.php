<?php

namespace Tests\Factories;

class ForumPostFactory
{
    public static function create(\PDO $pdo, $topicId, $userId, $title, $body)
    {
        $sql = 'INSERT INTO forum_posts 
                    (topic_id, post_poster, post_time, post_titel, post_text) 
                VALUES (?, ?, NOW(), ?, ?);';
        $query = $pdo->prepare($sql);
        $query->execute([
            $topicId, $userId, $title, $body
        ]);

        return $pdo->lastInsertId();
    }
}