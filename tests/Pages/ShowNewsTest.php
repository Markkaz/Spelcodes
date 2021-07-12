<?php

namespace Tests\Pages;

use Tests\Factories\NewsCommentFactory;
use Tests\Factories\NewsFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class ShowNewsTest extends TestCase
{
    private $userId;
    private $newsId;

    protected function setUp()
    {
        parent::setUp();

        $this->userId = UserFactory::create(
            self::$pdo,
            'Mark',
            'secret',
            'example@example.com',
            '127.0.0.1'
        );

        $this->newsId = NewsFactory::create(
            self::$pdo,
            $this->userId,
            'News item',
            'The content of the news item'
        );
    }


    /** @test */
    public function it_shows_the_requested_newsitem()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../shownieuws.php',
            ['id' => $this->newsId]
        );

        $this->assertContains('News item', $page);
        $this->assertContains('The content of the news item', $page);
    }

    /** @test */
    public function it_shows_comments_under_a_newsitem()
    {
        NewsCommentFactory::create(
            self::$pdo,
            $this->newsId,
            $this->userId,
            'Nice article!'
        );

        $newsId = NewsFactory::create(
            self::$pdo,
            $this->newsId,
            'Different news item',
            'Different news item'
        );
        NewsCommentFactory::create(
            self::$pdo,
            $newsId,
            $this->userId,
            'Not visible'
        );

        $page = $this->visitPage(
            __DIR__ . '/../../shownieuws.php',
            ['id' => $this->newsId]
        );

        $this->assertContains('Nice article!', $page);
        $this->assertNotContains('Not visible', $page);
    }

    /** @test */
    public function it_shows_404_when_no_newsid_is_provided()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../shownieuws.php'
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_newsid_doesnt_exist()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../shownieuws.php',
            ['id' => 999]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_edit_comment_button_for_users_own_comments()
    {
        $userId = $this->login();

        $commentId = NewsCommentFactory::create(
            self::$pdo,
            $this->newsId,
            $userId,
            'Nice article!'
        );

        $page = $this->visitPage(
            __DIR__ . '/../../shownieuws.php',
            ['id' => $this->newsId]
        );

        $this->assertContains('nieuwsEdit.php?id=' . $commentId, $page);
    }

    /** @test */
    public function it_shows_edit_comment_button_for_user_with_permissions()
    {
        $this->login(null, Permissions::MANAGE_COMMENTS);

        $commentId = NewsCommentFactory::create(
            self::$pdo,
            $this->newsId,
            $this->userId,
            'Nice article!'
        );

        $page = $this->visitPage(
            __DIR__ . '/../../shownieuws.php',
            ['id' => $this->newsId]
        );

        $this->assertContains('nieuwsEdit.php?id=' . $commentId, $page);
    }

    /** @test */
    public function it_doesnt_show_edit_comment_button_for_not_logged_in_user()
    {
        $commentId = NewsCommentFactory::create(
            self::$pdo,
            $this->newsId,
            $this->userId,
            'Nice article!'
        );

        $page = $this->visitPage(
            __DIR__ . '/../../shownieuws.php',
            ['id' => $this->newsId]
        );

        $this->assertNotContains('nieuwsEdit.php?id=' . $commentId, $page);
    }

    /** @test */
    public function it_doesnt_show_edit_comment_button_for_other_users_comments()
    {
        $this->login();

        $commentId = NewsCommentFactory::create(
            self::$pdo,
            $this->newsId,
            $this->userId,
            'Nice article!'
        );

        $page = $this->visitPage(
            __DIR__ . '/../../shownieuws.php',
            ['id' => $this->newsId]
        );

        $this->assertNotContains('nieuwsEdit.php?id=' . $commentId, $page);
    }
}
