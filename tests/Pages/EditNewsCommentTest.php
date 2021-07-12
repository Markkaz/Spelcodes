<?php

namespace Tests\Pages;

use Tests\Factories\NewsCommentFactory;
use Tests\Factories\NewsFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class EditNewsCommentTest extends TestCase
{
    private $userId;
    private $newsId;
    private $commentId;

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
            'News title',
            'Some content'
        );

        $this->commentId = NewsCommentFactory::create(
            self::$pdo,
            $this->newsId,
            $this->userId,
            'Nice comment'
        );
    }

    /** @test */
    public function it_shows_the_edit_form_when_user_owns_comment()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../nieuwsEdit.php',
            ['id' => $this->commentId]
        );

        $this->assertContains('Reactie bewerken', $page);
        $this->assertContains('Nice comment', $page);
    }

    /** @test */
    public function it_shows_the_edit_form_when_the_user_is_admin()
    {
        $this->login(null, Permissions::MANAGE_COMMENTS);

        $page = $this->visitPage(
            __DIR__ . '/../../nieuwsEdit.php',
            ['id' => $this->commentId]
        );

        $this->assertContains('Reactie bewerken', $page);
        $this->assertContains('Nice comment', $page);
    }

    /** @test */
    public function it_shows_404_when_user_isnt_logged_in()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../nieuwsEdit.php',
            ['id' => $this->commentId]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_user_is_logged_in_but_isnt_owner_of_the_comment_and_isnt_admin()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../nieuwsEdit.php',
            ['id' => $this->commentId]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_id_parameter_is_missing()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../nieuwsEdit.php'
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_edits_the_comment_when_user_edits_its_own_comment()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../nieuwsEdit.php',
            ['id' => $this->commentId],
            ['bericht' => 'New comment']
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('nieuwsreacties', [
            'nieuwsid' => $this->newsId,
            'bericht' => 'New comment'
        ]);
    }

    /** @test */
    public function it_edits_comment_when_user_is_admin()
    {
        $this->login(null, Permissions::MANAGE_COMMENTS);

        $page = $this->visitPage(
            __DIR__ . '/../../nieuwsEdit.php',
            ['id' => $this->commentId],
            ['bericht' => 'New comment']
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('nieuwsreacties', [
            'nieuwsid' => $this->newsId,
            'bericht' => 'New comment'
        ]);
    }

    /** @test */
    public function it_shows_404_when_unauthorised_user_edits_the_comment()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../nieuwsEdit.php',
            ['id' => $this->commentId],
            ['bericht' => 'New comment']
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('nieuwsreacties', [
            'nieuwsid' => $this->newsId,
            'bericht' => 'Nice comment'
        ]);
    }
}
