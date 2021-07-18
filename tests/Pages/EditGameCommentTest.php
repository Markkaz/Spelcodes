<?php

namespace Tests\Pages;

use Tests\Factories\CommentFactory;
use Tests\Factories\ConsoleFactory;
use Tests\Factories\GameFactory;
use Tests\Factories\TopicFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class EditGameCommentTest extends TestCase
{
    private $userId;
    private $consoleId;
    private $gameId;
    private $topicId;
    private $commentId;

    public static function getTables()
    {
        return [
            'users',
            'consoles',
            'spellen',
            'topics',
            'spellenhulp',
            'berichten',
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

        $this->consoleId = ConsoleFactory::create(
            self::$pdo,
            'Xbox'
        );

        $this->gameId = GameFactory::create(
            self::$pdo,
            $this->consoleId,
            'Halo',
            'halo',
            'Bungie',
            'Microsoft',
            'https://bungie.com',
            'https://microsoft.com'
        );

        $this->topicId = TopicFactory::create(
            self::$pdo,
            $this->userId,
            'Reviews',
            'A really nice game'
        );
        TopicFactory::attach(self::$pdo, $this->topicId, $this->gameId);

        $this->commentId = CommentFactory::create(
            self::$pdo,
            $this->topicId,
            $this->userId,
            'Nice comment'
        );
    }


    /** @test */
    public function it_shows_the_edit_comment_form_for_the_owner_of_the_comment()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../reactieEdit.php',
            [
                'id' => $this->commentId,
                'spelid' => $this->gameId,
                'topicid' => $this->topicId
            ]
        );

        $this->assertContains('Bericht bewerken', $page);
        $this->assertContains('Nice comment', $page);
    }

    /** @test */
    public function it_shows_the_edit_comment_form_for_the_admin()
    {
        $this->login(null, Permissions::FORUM_MODERATOR);

        $page = $this->visitPage(
            __DIR__ . '/../../reactieEdit.php',
            [
                'id' => $this->commentId,
                'spelid' => $this->gameId,
                'topicid' => $this->topicId
            ]
        );

        $this->assertContains('Bericht bewerken', $page);
        $this->assertContains('Nice comment', $page);
    }

    /** @test */
    public function it_shows_404_for_an_unauthorised_user()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../reactieEdit.php',
            [
                'id' => $this->commentId,
                'spelid' => $this->gameId,
                'topicid' => $this->topicId
            ]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_id_parameter_is_missing()
    {
        $this->login(null, Permissions::FORUM_MODERATOR);

        $page = $this->visitPage(
            __DIR__ . '/../../reactieEdit.php',
            [
                'spelid' => $this->gameId,
                'topicid' => $this->topicId
            ]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_topicid_parameter_is_missing()
    {
        $this->login(null, Permissions::FORUM_MODERATOR);

        $page = $this->visitPage(
            __DIR__ . '/../../reactieEdit.php',
            [
                'id' => $this->commentId,
                'spelid' => $this->gameId,
            ]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_spelid_parameter_is_missing()
    {
        $this->login(null, Permissions::FORUM_MODERATOR);

        $page = $this->visitPage(
            __DIR__ . '/../../reactieEdit.php',
            [
                'id' => $this->commentId,
                'topicid' => $this->topicId
            ]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_user_isnt_logged_in()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../reactieEdit.php',
            [
                'id' => $this->commentId,
                'spelid' => $this->gameId,
                'topicid' => $this->topicId
            ]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_comment_doesnt_exist()
    {
        $this->login(null, Permissions::FORUM_MODERATOR);

        $page = $this->visitPage(
            __DIR__ . '/../../reactieEdit.php',
            [
                'id' => 999,
                'spelid' => $this->gameId,
                'topicid' => $this->topicId
            ]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_comment_isnt_associated_with_topic()
    {
        $topicId = TopicFactory::create(
            self::$pdo,
            $this->userId,
            'Another review',
            'Terrible game'
        );
        TopicFactory::attach(self::$pdo, $topicId, $this->gameId);

        $this->login(null, Permissions::FORUM_MODERATOR);

        $page = $this->visitPage(
            __DIR__ . '/../../reactieEdit.php',
            [
                'id' => $this->commentId,
                'spelid' => $this->gameId,
                'topicid' => $topicId
            ]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_topic_isnt_associated_with_game()
    {
        TopicFactory::detach(self::$pdo, $this->topicId, $this->gameId);

        $this->login(null, Permissions::FORUM_MODERATOR);

        $page = $this->visitPage(
            __DIR__ . '/../../reactieEdit.php',
            [
                'id' => $this->commentId,
                'spelid' => $this->gameId,
                'topicid' => $this->topicId
            ]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_updates_comment_when_authorised_user_updates_it()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../reactieEdit.php',
            [
                'id' => $this->commentId,
                'spelid' => $this->gameId,
                'topicid' => $this->topicId
            ],
            [
                'bericht' => 'Updated comment'
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('berichten', [
            'berichtid' => $this->commentId,
            'bericht' => 'Updated comment'
        ]);
    }
}
