<?php

namespace Tests\Forum\Admin;

use Tests\Factories\ForumCategoryFactory;
use Tests\Factories\ForumFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

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

        $generalId = ForumCategoryFactory::create(self::$pdo, 'General', 2);
        $gamesId = ForumCategoryFactory::create(self::$pdo, 'Games', 1);

        ForumFactory::create(
            self::$pdo,
            $generalId,
            'General chat',
            'Chatting about everything'
        );
        ForumFactory::create(
            self::$pdo,
            $gamesId,
            'Xbox games',
            'Topics about Xbox games'
        );
    }

    /** @test */
    public function it_shows_categories_and_forums()
    {
        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/index.php'
        );

        $this->assertContainsInOrder([
            'Games',
            'Xbox games', 'Topics about Xbox games',
            'General',
            'General chat',
            'Chatting about everything'
        ], $page);
    }

    /** @test */
    public function it_redirects_guests_to_login_form()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/index.php'
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_to_unauthorised_users()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/index.php'
        );

        $this->assertEquals('', $page);
    }
}
