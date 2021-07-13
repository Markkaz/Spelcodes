<?php

namespace Tests\Pages;

use Tests\Factories\CommentFactory;
use Tests\Factories\ConsoleFactory;
use Tests\Factories\GameFactory;
use Tests\Factories\TopicFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class GameTest extends TestCase
{
    private $consoleId;
    private $gameId;

    public static function getTables()
    {
        return [
            'consoles',
            'spellen',
            'stemmen',
            'users',
            'topics',
            'spellenhulp',
            'berichten',
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->consoleId = ConsoleFactory::create(self::$pdo, 'Xbox');
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
    }


    /** @test */
    public function it_shows_game_details()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../gameview.php',
            ['id' => $this->gameId]
        );

        $this->assertContains('Halo', $page);
        $this->assertContains('Bungie', $page);
        $this->assertContains('Microsoft', $page);
    }

    /** @test */
    public function it_shows_404_when_the_gameid_isnt_provided()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../gameview.php'
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_the_game_doesnt_exist()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../gameview.php',
            ['id' => 9999]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_a_voting_form_when_not_voted_yet()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../gameview.php',
            ['id' => $this->gameId]
        );

        $this->assertContains('stemmen.php?spelid=' . $this->gameId, $page);
    }

    /** @test */
    public function it_doesnt_show_a_voting_form_when_already_voted()
    {
        $this->vote($this->gameId);

        $page = $this->visitPage(
            __DIR__ . '/../../gameview.php',
            ['id' => $this->gameId]
        );

        $this->assertNotContains('stemmen.php?spelid=' . $this->gameId, $page);
    }

    /** @test */
    public function it_shows_topics_overview()
    {
        $userId = UserFactory::create(
            self::$pdo,
            'Mark',
            'secret',
            'example@example.com',
            '127.0.0.1'
        );

        $visibleTopic = TopicFactory::create(self::$pdo, $userId, 'Nice topic', 'Body of the topic');
        TopicFactory::attach(self::$pdo, $visibleTopic, $this->gameId);

        $invisibleTopic = TopicFactory::create(self::$pdo, $userId, 'Invisible topic', 'Body of the invisible topic');

        $page = $this->visitPage(
            __DIR__ . '/../../gameview.php',
            ['id' => $this->gameId]
        );

        $this->assertContains('Nice topic', $page);
        $this->assertNotContains('Invisible topic', $page);
    }

    /** @test */
    public function it_shows_topic_when_topic_id_is_provided()
    {
        $userId = UserFactory::create(
            self::$pdo,
            'Mark',
            'secret',
            'example@example.com',
            '127.0.0.1'
        );

        $topicId = TopicFactory::create(
            self::$pdo,
            $userId,
            'Nice topic',
            'Body of the topic'
        );
        TopicFactory::attach(self::$pdo, $topicId, $this->gameId);

        $page = $this->visitPage(
            __DIR__ . '/../../gameview.php',
            [
                'id' => $this->gameId,
                'topicid' => $topicId
            ]
        );

        $this->assertContains('Nice topic', $page);
        $this->assertContains('Body of the topic', $page);
    }

    /** @test */
    public function it_gives_404_when_topic_doesnt_exist()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../gameview.php',
            [
                'id' => $this->gameId,
                'topicid' => 999
            ]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_gives_404_when_topic_is_not_linked_to_requested_game()
    {
        $userId = UserFactory::create(
            self::$pdo,
            'Mark',
            'secret',
            'example@example.com',
            '127.0.0.1'
        );

        $topicId = TopicFactory::create(
            self::$pdo,
            $userId,
            'Nice topic',
            'Body of the topic'
        );

        $page = $this->visitPage(
            __DIR__ . '/../../gameview.php',
            [
                'id' => $this->gameId,
                'topicid' => $topicId
            ]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_comments_underneath_a_topic()
    {
        $userId = UserFactory::create(
            self::$pdo,
            'Mark',
            'secret',
            'example@example.com',
            '127.0.0.1'
        );

        $topicId = TopicFactory::create(
            self::$pdo,
            $userId,
            'Nice topic',
            'Body of the topic'
        );
        TopicFactory::attach(self::$pdo, $topicId, $this->gameId);

        CommentFactory::create(self::$pdo, $topicId, $userId, 'A random comment');

        $page = $this->visitPage(
            __DIR__ . '/../../gameview.php',
            [
                'id' => $this->gameId,
                'topicid' => $topicId
            ]
        );

        $this->assertContains('A random comment', $page);
    }

    /** @test */
    public function it_shows_edit_button_for_comment_for_logged_in_users_comments()
    {
        $userId = $this->login();

        $topicId = TopicFactory::create(
            self::$pdo,
            $userId,
            'Nice topic',
            'Body of the topic'
        );
        TopicFactory::attach(self::$pdo, $topicId, $this->gameId);

        $commentId = CommentFactory::create(self::$pdo, $topicId, $userId, 'A random comment');

        $page = $this->visitPage(
            __DIR__ . '/../../gameview.php',
            [
                'id' => $this->gameId,
                'topicid' => $topicId
            ]
        );

        $this->assertContains(
            'reactieEdit.php?id='.$commentId.'&spelid='.$this->gameId,
            $page
        );
    }

    /** @test */
    public function it_shows_edit_button_for_user_with_manage_comments_rights()
    {
        $this->login(null, Permissions::MANAGE_COMMENTS);

        $userId = UserFactory::create(
            self::$pdo,
            'Charly',
            'secret',
            'example@example.com',
            '127.0.0.1'
        );

        $topicId = TopicFactory::create(
            self::$pdo,
            $userId,
            'Nice topic',
            'Body of the topic'
        );
        TopicFactory::attach(self::$pdo, $topicId, $this->gameId);

        $commentId = CommentFactory::create(self::$pdo, $topicId, $userId, 'A random comment');

        $page = $this->visitPage(
            __DIR__ . '/../../gameview.php',
            [
                'id' => $this->gameId,
                'topicid' => $topicId
            ]
        );

        $this->assertContains(
            'reactieEdit.php?id='.$commentId.'&spelid='.$this->gameId,
            $page
        );
    }

    /** @test */
    public function it_doesnt_show_edit_button_for_not_loggedin_user()
    {
        $userId = UserFactory::create(
            self::$pdo,
            'Charly',
            'secret',
            'example@example.com',
            '127.0.0.1'
        );

        $topicId = TopicFactory::create(
            self::$pdo,
            $userId,
            'Nice topic',
            'Body of the topic'
        );
        TopicFactory::attach(self::$pdo, $topicId, $this->gameId);

        $commentId = CommentFactory::create(self::$pdo, $topicId, $userId, 'A random comment');

        $page = $this->visitPage(
            __DIR__ . '/../../gameview.php',
            [
                'id' => $this->gameId,
                'topicid' => $topicId
            ]
        );

        $this->assertNotContains(
            'reactieEdit.php?id='.$commentId.'&spelid='.$this->gameId,
            $page
        );
    }

    /** @test */
    public function it_doesnt_show_edit_button_for_comments_of_other_users()
    {
        $this->login();

        $userId = UserFactory::create(
            self::$pdo,
            'Charly',
            'secret',
            'example@example.com',
            '127.0.0.1'
        );

        $topicId = TopicFactory::create(
            self::$pdo,
            $userId,
            'Nice topic',
            'Body of the topic'
        );
        TopicFactory::attach(self::$pdo, $topicId, $this->gameId);

        $commentId = CommentFactory::create(self::$pdo, $topicId, $userId, 'A random comment');

        $page = $this->visitPage(
            __DIR__ . '/../../gameview.php',
            [
                'id' => $this->gameId,
                'topicid' => $topicId
            ]
        );

        $this->assertNotContains(
            'reactieEdit.php?id='.$commentId.'&spelid='.$this->gameId,
            $page
        );
    }

    protected function vote($gameId)
    {
        $sql = 'INSERT INTO stemmen (spelid, ip) VALUES (?, ?);';
        $query = self::$pdo->prepare($sql);
        $query->execute([
            $gameId,
            '127.0.0.1'
        ]);
    }
}
