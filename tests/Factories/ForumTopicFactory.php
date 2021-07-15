<?php

namespace Tests\Factories;

class ForumTopicFactory
{
    public static function create(\PDO $pdo, $forumId, $title, $userId, $status = true)
    {
        $sql = 'INSERT INTO forum_topics
                    (forum_id, topic_titel, topic_poster, topic_time, topic_replies, topic_status, topic_views, last_post, last_post_Time)
                VALUES 
                    (?, ?, ?, NOW(), 0, ?, 0, 0, NOW())';
        $query = $pdo->prepare($sql);
        $query->execute([
            $forumId, $title, $userId, $status
        ]);

        return $pdo->lastInsertId();
    }
}