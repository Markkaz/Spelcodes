<?php

namespace Tests\Factories;

class ForumCategoryFactory
{
    public static function create(\PDO $pdo, $title, $order)
    {
        $sql = 'INSERT INTO forum_categories (cat_titel, cat_order) VALUES (?, ?);';
        $query = $pdo->prepare($sql);
        $query->execute([
            $title,
            $order
        ]);

        return $pdo->lastInsertId();
    }
}