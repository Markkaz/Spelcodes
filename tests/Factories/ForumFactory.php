<?php

namespace Tests\Factories;

class ForumFactory
{
    public static function create(\PDO $pdo, $categoryId, $title, $description, $lastPostUserId = null)
    {
        $sql = 'INSERT INTO forum_forums 
                    (cat_id, forum_titel, forum_text, last_post, last_post_time)
                VALUES 
                    (?, ?, ?, ?, NOW())';
        $query = $pdo->prepare($sql);
        $query->execute([
            $categoryId,
            $title,
            $description,
            $lastPostUserId
        ]);

        return $pdo->lastInsertId();
    }
}