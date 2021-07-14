<?php

namespace Tests\Forum;

use Tests\Factories\ForumCategoryFactory;
use Tests\Factories\ForumFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;

class IndexTest extends TestCase
{
    public static function getTables()
    {
        return [
            'users',
            'forum_categories',
            'forum_forums',
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $user = UserFactory::create(
            self::$pdo,
            'Webmaster',
            'secret',
            'example@example.com',
            '127.0.0.1'
        );

        $category1 = ForumCategoryFactory::create(
            self::$pdo,
            'General',
            0
        );

        $category2 = ForumCategoryFactory::create(
            self::$pdo,
            'Games chat',
            5
        );

        ForumFactory::create(
            self::$pdo,
            $category2,
            'Xbox chat',
            'Chat about Xbox games'
        );

        ForumFactory::create(
            self::$pdo,
            $category1,
            'Website updates',
            'Updates about Spelcodes',
            $user
        );
    }

    /** @test */
    public function it_shows_the_forum_categories()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../forum/index.php'
        );

        $this->assertContains('Forum - Index', $page);
        $this->assertContainsInOrder(['General', 'Games chat'], $page);
    }

    /** @test */
    public function it_shows_forum_per_category()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../forum/index.php'
        );

        $this->assertContains('Webmaster', $page);
        $this->assertContainsInOrder([
            'Website updates',
            'Updates about Spelcodes',
            'Xbox chat',
            'Chat about Xbox games',
        ], $page);
    }
}
