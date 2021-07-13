<?php

namespace Tests\Pages;

use Tests\Factories\ConsoleFactory;
use Tests\Factories\GameFactory;
use Tests\Factories\TopicFactory;
use Tests\TestCase;

class AddGameCommentTest extends TestCase
{
    private $userId;
    private $consoleId;
    private $gameId;
    private $topicId;

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

        $this->userId = $this->login();

        $this->consoleId = ConsoleFactory::create(self::$pdo, 'Xbox');

        $this->gameId = GameFactory::create(
            self::$pdo,
            $this->consoleId,
            'Halo',
            'halo',
            'Developer',
            'Publisher',
            'http://developer.com',
            'http://publisher.com'
        );

        $this->topicId = TopicFactory::create(
            self::$pdo,
            $this->userId,
            'Review',
            'It is a really nice game!'
        );
        TopicFactory::attach(
            self::$pdo,
            $this->topicId,
            $this->gameId
        );
    }

    /** @test */
    public function it_adds_a_game_comment_for_a_logged_in_user()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../addpost.php',
            [
                'id' => $this->gameId,
                'topicid' => $this->topicId
            ],
            [
                'reactie' => 'Really good game!'
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('berichten', [
            'topicid' => $this->topicId,
            'userid' => $this->userId,
            'bericht' => 'Really good game!'
        ]);
        $this->assertDatabaseHas('users', [
            'username' => 'Mark',
            'posts' => 1
        ]);
    }

    /** @test */
    public function it_shows_404_when_game_id_is_missing()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../addpost.php',
            [
                'topicid' => $this->topicId
            ],
            [
                'reactie' => 'Really good game!'
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseMissing('berichten', [
            'topicid' => $this->topicId,
            'userid' => $this->userId,
            'bericht' => 'Really good game!'
        ]);
        $this->assertDatabaseHas('users', [
            'username' => 'Mark',
            'posts' => 0
        ]);
    }

    /** @test */
    public function it_shows_404_when_topic_id_is_missing()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../addpost.php',
            [
                'id' => $this->gameId
            ],
            [
                'reactie' => 'Really good game!'
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseMissing('berichten', [
            'topicid' => $this->topicId,
            'userid' => $this->userId,
            'bericht' => 'Really good game!'
        ]);
        $this->assertDatabaseHas('users', [
            'username' => 'Mark',
            'posts' => 0
        ]);
    }

    /** @test */
    public function it_shows_404_when_topic_doesnt_exist()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../addpost.php',
            [
                'id' => $this->gameId,
                'topicid' => 999
            ],
            [
                'reactie' => 'Really good game!'
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseMissing('berichten', [
            'topicid' => 999,
            'userid' => $this->userId,
            'bericht' => 'Really good game!'
        ]);
        $this->assertDatabaseHas('users', [
            'username' => 'Mark',
            'posts' => 0
        ]);
    }

    /** @test */
    public function it_shows_404_when_topic_doesnt_exist_for_game()
    {
        $newTopicId = TopicFactory::create(
            self::$pdo,
            $this->userId,
            'Different topic',
            'The content of the different topic'
        );

        $page = $this->visitPage(
            __DIR__ . '/../../addpost.php',
            [
                'id' => $this->gameId,
                'topicid' => $newTopicId
            ],
            [
                'reactie' => 'Really good game!'
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseMissing('berichten', [
            'topicid' => $newTopicId,
            'userid' => $this->userId,
            'bericht' => 'Really good game!'
        ]);
        $this->assertDatabaseHas('users', [
            'username' => 'Mark',
            'posts' => 0
        ]);
    }

    /** @test */
    public function it_shows_404_when_comment_data_is_missing()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../addpost.php',
            [
                'id' => $this->gameId,
                'topicid' => $this->topicId
            ],
            []
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseMissing('berichten', [
            'topicid' => $this->topicId,
            'userid' => $this->userId,
            'bericht' => ''
        ]);
        $this->assertDatabaseHas('users', [
            'username' => 'Mark',
            'posts' => 0
        ]);
    }

    /** @test */
    public function it_redirects_to_login_when_user_isnt_logged_in()
    {
        $this->logout();

        $page = $this->visitPage(
            __DIR__ . '/../../addpost.php',
            [
                'id' => $this->gameId,
                'topicid' => $this->topicId
            ],
            [
                'reactie' => 'Really good game!'
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseMissing('berichten', [
            'topicid' => $this->topicId,
            'userid' => $this->userId,
            'bericht' => 'Really good game!'
        ]);
        $this->assertDatabaseHas('users', [
            'username' => 'Mark',
            'posts' => 0
        ]);
    }
}
