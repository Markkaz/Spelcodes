<?php

namespace Tests\Forum;

use Tests\Factories\ForumCategoryFactory;
use Tests\Factories\ForumFactory;
use Tests\Factories\ForumTopicFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class MoveTopicTest extends TestCase
{
    private $topicId;
    private $otherForum;
    private $forumId;

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

        $userId = UserFactory::create(
            self::$pdo,
            'Yogi',
            'secret',
            'yogi@bear.com',
            '127.0.0.1'
        );

        $categoryId = ForumCategoryFactory::create(
            self::$pdo,
            'General',
            0
        );

        $this->forumId = ForumFactory::create(
            self::$pdo,
            $categoryId,
            'General',
            'General chat'
        );

        $this->otherForum = ForumFactory::create(
            self::$pdo,
            $categoryId,
            'Different',
            'Different forum'
        );

        $this->topicId = ForumTopicFactory::create(
            self::$pdo,
            $this->forumId,
            'Interesting topic',
            $userId
        );
    }

    /** @test */
    public function it_shows_the_topic_move_form_to_topic_moderator()
    {
        $this->login(null, Permissions::FORUM_MODERATOR);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicMove.php',
            ['topicid' => $this->topicId]
        );

        $this->assertContains('Interesting topic', $page);
        $this->assertContainsInOrder([
            'Different',
            'General'
        ], $page);
    }

    /** @test */
    public function it_allows_the_topic_owner_to_move_a_topic()
    {
        $this->login(null, Permissions::FORUM_MODERATOR);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicMove.php',
            ['topicid' => $this->topicId],
            ['forumid' => $this->otherForum]
        );

        $this->assertDatabaseHas('forum_topics', [
            'topic_id' => $this->topicId,
            'forum_id' => $this->otherForum
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_redirects_to_login_for_guest_users()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicMove.php',
            ['topicid' => $this->topicId],
            ['forumid' => $this->otherForum]
        );

        $this->assertDatabaseHas('forum_topics', [
            'topic_id' => $this->topicId,
            'forum_id' => $this->forumId
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_to_unauthorised_users()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicMove.php',
            ['topicid' => $this->topicId],
            ['forumid' => $this->otherForum]
        );

        $this->assertDatabaseHas('forum_topics', [
            'topic_id' => $this->topicId,
            'forum_id' => $this->forumId
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_topicid_parameter_is_missing()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicMove.php'
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_topic_doesnt_exist()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicMove.php',
            ['topicid' => 999]
        );

        $this->assertEquals('', $page);
    }
}
