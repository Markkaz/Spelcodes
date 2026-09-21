<?php

namespace Tests\Forum;

use Tests\Factories\ForumCategoryFactory;
use Tests\Factories\ForumFactory;
use Tests\Factories\ForumTopicFactory;
use Tests\Factories\TopicFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;

class IndexTest extends TestCase
{
    private $forum1;
    private $forum2;

    public static function getTables()
    {
        return [
            'users',
            'forum_categories',
            'forum_forums',
            'forum_topics',
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

        $this->forum1 = ForumFactory::create(
            self::$pdo,
            $category2,
            'Xbox chat',
            'Chat about Xbox games'
        );

        $this->forum2 = ForumFactory::create(
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

    /** @test */
    public function it_shows_number_of_topics_in_a_forum()
    {
        $userId = $this->login();

        ForumTopicFactory::create(
            self::$pdo,
            $this->forum1,
            'A random topic',
            $userId,
            true
        );
        ForumTopicFactory::create(
            self::$pdo,
            $this->forum1,
            'A random topic 2',
            $userId,
            true
        );

        $page = $this->visitPage(__DIR__ . '/../../forum/index.php');

        $this->assertContainsInOrder(['Website updates', '<td background="../img/patroon.gif" width=40 align=center>0</td>', 'Xbox chat', '<td background="../img/patroon.gif" width=40 align=center>2</td>'], $page);
    }
}
