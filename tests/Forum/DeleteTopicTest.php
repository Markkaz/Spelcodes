<?php

namespace Tests\Forum;

use Tests\Factories\ForumCategoryFactory;
use Tests\Factories\ForumFactory;
use Tests\Factories\ForumPostFactory;
use Tests\Factories\ForumTopicFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class DeleteTopicTest extends TestCase
{
    private $userId;
    private $topicId;

    public static function getTables()
    {
        return [
            'users',
            'forum_categories',
            'forum_forums',
            'forum_topics',
            'forum_posts'
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->userId = UserFactory::create(
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

        $forumId = ForumFactory::create(
            self::$pdo,
            $categoryId,
            'General',
            'General chat'
        );

        $this->topicId = ForumTopicFactory::create(
            self::$pdo,
            $forumId,
            'Interesting topic',
            $this->userId
        );

        ForumPostFactory::create(
            self::$pdo,
            $this->topicId,
            $this->userId,
            'A random reply',
            'With some random argument'
        );
    }

    /** @test */
    public function it_shows_delete_topic_form_to_forum_moderator()
    {
        $this->login(null, Permissions::FORUM_MODERATOR);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicVerwijder.php',
            ['topicid' => $this->topicId]
        );

        $this->assertContains('topicID <b>'.$this->topicId.'</b>', $page);
    }

    /** @test */
    public function it_shows_delete_topic_form_to_topic_owner()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicVerwijder.php',
            ['topicid' => $this->topicId]
        );

        $this->assertContains('topicID <b>'.$this->topicId.'</b>', $page);
    }

    /** @test */
    public function it_deletes_forum_topic_on_request_of_the_forum_moderator()
    {
        $this->login(null, Permissions::FORUM_MODERATOR);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicVerwijder.php',
            ['topicid' => $this->topicId],
            ['delete' => true]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseMissing('forum_posts', [
            'post_titel' => 'A random reply',
            'post_text' => 'With some random argument'
        ]);
        $this->assertDatabaseMissing('forum_topics', [
            'topic_titel' => 'Interesting topic'
        ]);
    }

    /** @test */
    public function it_delets_forum_topic_on_request_of_the_topic_owner()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicVerwijder.php',
            ['topicid' => $this->topicId],
            ['delete' => true]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseMissing('forum_posts', [
            'post_titel' => 'A random reply',
            'post_text' => 'With some random argument'
        ]);
        $this->assertDatabaseMissing('forum_topics', [
            'topic_titel' => 'Interesting topic'
        ]);
    }

    /** @test */
    public function it_shows_404_when_topic_id_is_missing()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicVerwijder.php'
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_redirects_a_guest_to_the_login_form()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicVerwijder.php',
            ['topicid' => $this->topicId],
            ['delete' => true]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('forum_posts', [
            'post_titel' => 'A random reply',
            'post_text' => 'With some random argument'
        ]);
        $this->assertDatabaseHas('forum_topics', [
            'topic_titel' => 'Interesting topic'
        ]);
    }

    /** @test */
    public function it_shows_404_for_unauthorised_user()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicVerwijder.php',
            ['topicid' => $this->topicId],
            ['delete' => true]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('forum_posts', [
            'post_titel' => 'A random reply',
            'post_text' => 'With some random argument'
        ]);
        $this->assertDatabaseHas('forum_topics', [
            'topic_titel' => 'Interesting topic'
        ]);
    }

    /** @test */
    public function it_shows_404_when_forum_topic_doesnt_exist()
    {
        $this->login(null, Permissions::FORUM_MODERATOR);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicVerwijder.php',
            ['topic_id' => 999]
        );
        $this->assertDatabaseHas('forum_posts', [
            'post_titel' => 'A random reply',
            'post_text' => 'With some random argument'
        ]);
        $this->assertDatabaseHas('forum_topics', [
            'topic_titel' => 'Interesting topic'
        ]);

        $this->assertEquals('', $page);
    }
}
