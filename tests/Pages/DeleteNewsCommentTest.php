<?php

namespace Tests\Pages;

use Tests\Factories\NewsCommentFactory;
use Tests\Factories\NewsFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class DeleteNewsCommentTest extends TestCase
{
    private $userId;
    private $newsId;
    private $commentId;

    public static function getTables()
    {
        return [
            'users',
            'nieuws',
            'nieuwsreacties',
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->userId = UserFactory::create(
            self::$pdo,
            'Yogi',
            'secret',
            'example@example.com',
            '127.0.0.1'
        );

        $this->newsId = NewsFactory::create(
            self::$pdo,
            $this->userId,
            'A news item',
            'content of the news item'
        );

        $this->commentId = NewsCommentFactory::create(
            self::$pdo,
            $this->newsId,
            $this->userId,
            'Nice comment'
        );
    }


    /** @test */
    public function it_shows_the_delete_form_for_the_owner_of_the_comment()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../nieuwsDelete.php',
            ['id' => $this->commentId]
        );

        $this->assertContains('Reactie verwijderen', $page);
    }

    /** @test */
    public function it_shows_the_delete_form_for_the_admin_user()
    {
        $this->login(null, Permissions::FORUM_MODERATOR);

        $page = $this->visitPage(
            __DIR__ . '/../../nieuwsDelete.php',
            ['id' => $this->commentId]
        );

        $this->assertContains('Reactie verwijderen', $page);
    }

    /** @test */
    public function it_shows_404_for_an_unauthorised_user()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../nieuwsDelete.php',
            ['id' => $this->commentId]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_for_logged_out_user()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../nieuwsDelete.php',
            ['id' => $this->commentId]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_id_parameter_is_missing()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../nieuwsDelete.php'
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_comment_doesnt_exist()
    {
        $this->login(null, Permissions::FORUM_MODERATOR);

        $page = $this->visitPage(
            __DIR__ . '/../../nieuwsDelete.php',
            ['id' => 999]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_deletes_comment_when_owner_deletes_it()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../nieuwsDelete.php',
            ['id' => $this->commentId],
            ['delete' => true]
        );

        $this->assertEquals('', $page);

        $this->assertDatabaseMissing(
            'nieuwsreacties',
            ['reactieid' => $this->commentId]
        );
    }

    /** @test */
    public function it_deletes_comment_when_admin_deletes_it()
    {
        $this->login(null, Permissions::FORUM_MODERATOR);

        $page = $this->visitPage(
            __DIR__ . '/../../nieuwsDelete.php',
            ['id' => $this->commentId],
            ['delete' => true]
        );

        $this->assertEquals('', $page);

        $this->assertDatabaseMissing(
            'nieuwsreacties',
            ['reactieid' => $this->commentId]
        );
    }

    /** @test */
    public function it_shows_404_when_unauthorised_user_tries_to_delete_it()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../nieuwsDelete.php',
            ['id' => $this->commentId],
            ['delete' => true]
        );

        $this->assertEquals('', $page);

        $this->assertDatabaseHas(
            'nieuwsreacties',
            ['reactieid' => $this->commentId]
        );
    }
}
