<?php

namespace Tests\Pages;

use Tests\Factories\CommentFactory;
use Tests\Factories\ConsoleFactory;
use Tests\Factories\GameFactory;
use Tests\Factories\TopicFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class DeleteGameCommentTest extends TestCase
{
    private $userId;
    private $consoleId;
    private $gameId;
    private $topicId;
    private $commentId;

    protected function setUp()
    {
        parent::setUp();

        $this->userId = UserFactory::create(
            self::$pdo,
            'Harry',
            'secret',
            'example@example.com',
            '127.0.0.1'
        );

        $this->consoleId = ConsoleFactory::create(self::$pdo, 'Xbox');

        $this->gameId = GameFactory::create(
            self::$pdo,
            $this->consoleId,
            'Halo',
            'halo',
            'Bungie',
            'https://bungie.com',
            'Microsoft',
            'https://microsoft.com'
        );

        $this->topicId = TopicFactory::create(
            self::$pdo,
            $this->userId,
            'Review',
            'Really bad game'
        );
        TopicFactory::attach(self::$pdo, $this->topicId, $this->gameId);

        $this->commentId = CommentFactory::create(
            self::$pdo,
            $this->topicId,
            $this->userId,
            'Nice comment!'
        );
    }


    /** @test */
    public function it_allows_the_owner_of_a_comment_to_see_the_delete_form()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../reactieDelete.php',
            [
                'id' => $this->commentId,
                'topicid' => $this->topicId,
                'spelid' => $this->gameId
            ]
        );

        $this->assertContains('Bericht verwijderen', $page);
    }

    /** @test */
    public function it_allows_an_admin_to_see_the_delete_form()
    {
        $this->login(null, Permissions::MANAGE_COMMENTS);

        $page = $this->visitPage(
            __DIR__ . '/../../reactieDelete.php',
            [
                'id' => $this->commentId,
                'topicid' => $this->topicId,
                'spelid' => $this->gameId
            ]
        );

        $this->assertContains('Bericht verwijderen', $page);
    }

    /** @test */
    public function it_allows_the_owner_of_a_comment_to_delete_it()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../reactieDelete.php',
            [
                'id' => $this->commentId,
                'topicid' => $this->topicId,
                'spelid' => $this->gameId
            ],
            ['delete' => true]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseMissing('berichten', [
            'berichtid' => $this->commentId
        ]);
    }

    /** @test */
    public function it_allows_the_admin_to_delete_a_comment()
    {
        $this->login(null, Permissions::MANAGE_COMMENTS);

        $page = $this->visitPage(
            __DIR__ . '/../../reactieDelete.php',
            [
                'id' => $this->commentId,
                'topicid' => $this->topicId,
                'spelid' => $this->gameId
            ],
            ['delete' => true]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseMissing('berichten', [
            'berichtid' => $this->commentId
        ]);
    }

    /** @test */
    public function it_shows_404_for_not_logged_in_user()
    {
         $page = $this->visitPage(
            __DIR__ . '/../../reactieDelete.php',
            [
                'id' => $this->commentId,
                'topicid' => $this->topicId,
                'spelid' => $this->gameId
            ],
            ['delete' => true]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('berichten', [
            'berichtid' => $this->commentId
        ]);
    }

    /** @test */
    public function it_shows_404_when_unauthorised_user_tries_to_see_delete_form()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../reactieDelete.php',
            [
                'id' => $this->commentId,
                'topicid' => $this->topicId,
                'spelid' => $this->gameId
            ]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_unauthorised_user_tries_to_delete_comment()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../reactieDelete.php',
            [
                'id' => $this->commentId,
                'topicid' => $this->topicId,
                'spelid' => $this->gameId
            ],
            ['delete' => true]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('berichten', [
            'berichtid' => $this->commentId
        ]);
    }

    /** @test */
    public function it_shows_404_when_id_parameter_is_missing()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../reactieDelete.php',
            [
                'topicid' => $this->topicId,
                'spelid' => $this->gameId
            ],
            ['delete' => true]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('berichten', [
            'berichtid' => $this->commentId
        ]);
    }

    /** @test */
    public function it_shows_404_when_topicid_parameter_is_missing()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../reactieDelete.php',
            [
                'id' => $this->commentId,
                'spelid' => $this->gameId
            ],
            ['delete' => true]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('berichten', [
            'berichtid' => $this->commentId
        ]);
    }

    /** @test */
    public function it_shows_404_when_spelid_parameter_is_missing()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../reactieDelete.php',
            [
                'id' => $this->commentId,
                'topicid' => $this->topicId,
            ],
            ['delete' => true]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('berichten', [
            'berichtid' => $this->commentId
        ]);
    }

    /** @test */
    public function it_shows_404_when_comment_doesnt_exist()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../reactieDelete.php',
            [
                'id' => 999,
                'topicid' => $this->topicId,
                'spelid' => $this->gameId
            ]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_comment_isnt_associated_with_the_topic()
    {
        $topicId = TopicFactory::create(
            self::$pdo,
            $this->userId,
            'Another review',
            'Worst game ever'
        );
        TopicFactory::attach(self::$pdo, $topicId, $this->gameId);

        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../reactieDelete.php',
            [
                'id' => $this->commentId,
                'topicid' => $topicId,
                'spelid' => $this->gameId
            ],
            ['delete' => true]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('berichten', [
            'berichtid' => $this->commentId
        ]);
    }

    /** @test */
    public function it_shows_404_when_comment_isnt_associated_with_game()
    {
        $gameId = GameFactory::create(
            self::$pdo,
            $this->consoleId,
            'Sonic the Hedgehog',
            'sonic',
            'Sega',
            'Sega',
            'https://sega.com',
            'https://sega.com'
        );


        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../reactieDelete.php',
            [
                'id' => $this->commentId,
                'topicid' => $this->topicId,
                'spelid' => $gameId
            ],
            ['delete' => true]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('berichten', [
            'berichtid' => $this->commentId
        ]);
    }
}
