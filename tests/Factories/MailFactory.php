<?php
namespace Tests\Factories;

class MailFactory
{
    public static function create(\PDO $pdo, $subject, $body, $email) {
        $sql = "INSERT INTO mail (titel, bericht, email, gelezen) VALUES (?, ?, ?, false)";
        $query = $pdo->prepare($sql);
        $query->execute([$subject, $body, $email]);

        return $pdo->lastInsertId();
    }

    public static function read(\PDO $pdo, $mailId) {
        $sql = "UPDATE mail SET gelezen = 1 WHERE mailid = ?";
        $query = $pdo->prepare($sql);
        $query->execute([$mailId]);
    }
}