<?php


namespace Tests\Factories;


use Tests\Pages\IndexTest;

class UserFactory
{
    public static function create(\PDO $pdo, $name, $password, $email, $ip, $permissions = 0)
    {
        $sql = 'INSERT INTO users 
                    (username, password, email, ip, activate, permis, posts, datum) 
                VALUES 
                   (?, SHA2(?, 0), ?, ?, 1, ?, 0, NOW())';
        $query = $pdo->prepare($sql);
        $query->execute([
            $name,
            $password,
            $email,
            $ip,
            $permissions
        ]);

        return $pdo->lastInsertId();
    }
}