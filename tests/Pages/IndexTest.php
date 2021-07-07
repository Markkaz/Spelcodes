<?php

namespace Tests\Pages;

use Tests\Factories\NewsFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;

class IndexTest extends TestCase
{
    protected function tearDown()
    {
        parent::tearDown();

        session_abort();
    }

    /** @test */
    public function it_shows_top_5_last_news_items()
    {
        $userId = UserFactory::create(
            self::$pdo,
            'Mark',
            'newpassword',
            'example@example.com',
            '127.0.0.1'
        );

        NewsFactory::create(self::$pdo, $userId, 'Invisible news item', 'This news item is invisible');
        foreach (range(1, 5) as $i) {
            NewsFactory::create(self::$pdo, $userId, 'News item '.$i, 'Body of news item '.$i);
        }

        $page = $this->visitPage(
            __DIR__ . '/../../index.php'
        );

        $this->assertContains('News item', $page);
        $this->assertNotContains('Invisible news item', $page);
    }

    /** @test */
    public function it_shows_amount_of_comments_with_a_news_item()
    {
        $userId = UserFactory::create(
            self::$pdo,
            'Mark',
            'newspassword',
            'example@example.com',
            '127.0.0.1'
        );

        $newsId = NewsFactory::create(self::$pdo, $userId, 'A news item', 'And some content on the news item');
        foreach (range(1, 5) as $item) {
            $this->createNewsComment($newsId, $userId, 'A reply');
        }

        $page = $this->visitPage(
            __DIR__ . '/../../index.php'
        );

        $this->assertContains('(5)', $page);
    }

    /** @test */
    public function it_shows_three_highlighted_games()
    {
        $consoleId = $this->createConsole('Xbox');

        foreach(range(1, 3) as $i) {
            $gameId = $this->createGame(
                $consoleId,
                'Halo ' . $i,
                'halo',
                'Bungie',
                'Microsoft',
                'https://bungie.com',
                'https://microsoft.com'
            );

            $this->highlightGame($consoleId, $gameId);
        }

        // Visit page
        $page = $this->visitPage(
            __DIR__ . '/../../index.php'
        );

        // Check if highlighted games are on homepage
        $this->assertContains('Halo 1', $page);
        $this->assertContains('Halo 2', $page);
        $this->assertContains('Halo 3', $page);
        $this->assertContains('Xbox', $page);
    }

    protected function createUser($name, $password, $email, $ip)
    {
        return UserFactory::create(self::$pdo, $name, $password, $email, $ip);
    }

    protected function createConsole($name)
    {
        $sql = 'INSERT INTO consoles (naam) VALUES (?)';
        $query = self::$pdo->prepare($sql);
        $query->execute([$name]);

        return self::$pdo->lastInsertId();
    }

    protected function createGame($consoleId, $name, $directory, $developer, $publisher, $developerUrl, $publisherUrl)
    {
        $sql = 'INSERT INTO spellen 
                    (consoleid, naam, map, developer, publisher, developerurl, publisherurl, rating, stemmen) 
                VALUES 
                    (?, ?, ?, ?, ?, ?, ?, 0, 0)';
        $query = self::$pdo->prepare($sql);
        $query->execute([
            $consoleId,
            $name,
            $directory,
            $developer,
            $publisher,
            $developerUrl,
            $publisherUrl
        ]);

        return self::$pdo->lastInsertId();
    }

    protected function highlightGame($consoleId, $gameId)
    {
        $sql = 'INSERT INTO spellenview (consoleid, spelid) VALUES (?, ?);';
        $query = self::$pdo->prepare($sql);
        $query->execute([$consoleId, $gameId]);
    }

    protected function createNewsComment($newsId, $userId, $body)
    {
        $sql = 'INSERT INTO nieuwsreacties 
                    (nieuwsid, userid, bericht, datum, tijd) 
                VALUES 
                    (?, ?, ?, NOW(), NOW());';
        $query = self::$pdo->prepare($sql);
        $query->execute([
            $newsId,
            $userId,
            $body
        ]);
    }
}
