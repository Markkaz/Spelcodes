<?php

namespace Tests\Pages;

use Tests\Factories\NewsFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;

class NewsArchiveTest extends TestCase
{
    /** @test */
    public function it_shows_news_messages()
    {
        $userId = UserFactory::create(
            self::$pdo,
            'Mark',
            'random',
            'example@exmple.com',
            '127.0.0.1'
        );

        NewsFactory::create(
            self::$pdo,
            $userId,
            'First news item',
            'The body of the first news item'
        );
        NewsFactory::create(
            self::$pdo,
            $userId,
            'Second news item',
            'The body of the second news item'
        );

        $page = $this->visitPage(
            __DIR__ . '/../../archief.php'
        );

        $this->assertContains('First news item', $page);
        $this->assertContains('Second news item', $page);
    }
}
