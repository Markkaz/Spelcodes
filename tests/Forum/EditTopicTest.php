<?php

namespace Tests\Forum;

use Tests\Factories\ForumCategoryFactory;
use Tests\Factories\ForumFactory;
use Tests\Factories\ForumTopicFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class EditTopicTest extends TestCase
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
    }

    /** @test */
    public function it_shows_edit_form_to_topic_owner()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicBewerk.php',
            ['topicid' => $this->topicId]
        );

        $this->assertContains('Interesting topic', $page);
    }

    /** @test */
    public function it_shows_edit_form_to_forum_moderator()
    {
        $this->login(null, Permissions::MANAGE_COMMENTS);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicBewerk.php',
            ['topicid' => $this->topicId]
        );

        $this->assertContains('Interesting topic', $page);
    }

    /** @test */
    public function it_allows_topic_owner_to_edit_topic()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicBewerk.php',
            ['topicid' => $this->topicId],
            ['titel' => 'Edited title']
        );

        $this->assertDatabaseHas('forum_topics', [
            'topic_id' => $this->topicId,
            'topic_titel' => 'Edited title'
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_allows_forum_moderator_to_edit_topic()
    {
        $this->login(null, Permissions::MANAGE_COMMENTS);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicBewerk.php',
            ['topicid' => $this->topicId],
            ['titel' => 'Edited title']
        );

        $this->assertDatabaseHas('forum_topics', [
            'topic_id' => $this->topicId,
            'topic_titel' => 'Edited title'
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_topicid_parameter_is_missing()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicBewerk.php',
            [],
            ['titel' => 'Edited title']
        );

        $this->assertDatabaseHas('forum_topics', [
            'topic_id' => $this->topicId,
            'topic_titel' => 'Interesting topic'
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_topic_doesnt_exist()
    {
        $this->login(null, Permissions::MANAGE_COMMENTS);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicBewerk.php',
            ['topicid' => 999]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_redirects_to_login_form_when_user_isnt_logged_in()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicBewerk.php',
            ['topicid' => $this->topicId],
            ['titel' => 'Edited title']
        );

        $this->assertDatabaseHas('forum_topics', [
            'topic_id' => $this->topicId,
            'topic_titel' => 'Interesting title'
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_user_doesnt_have_permission()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicBewerk.php',
            ['topicid' => $this->topicId],
            ['titel' => 'Edited title']
        );

        $this->assertDatabaseHas('forum_topics', [
            'topic_id' => $this->topicId,
            'topic_titel' => 'Interesting title'
        ]);
        $this->assertEquals('', $page);
    }
}
