<?php

namespace Tests\Forum;

use Tests\Factories\ForumCategoryFactory;
use Tests\Factories\ForumFactory;
use Tests\TestCase;

class AddTopicTest extends TestCase
{
    private $forumId;

    public static function getTables()
    {
        return [
            'users',
            'forum_categories',
            'forum_forums',
            'forum_topics',
            'forum_posts',
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $categoryId = ForumCategoryFactory::create(self::$pdo, 'General', 0);

        $this->forumId = ForumFactory::create(
            self::$pdo,
            $categoryId,
            'General',
            'The general forum'
        );
    }

    /** @test */
    public function it_allows_logged_in_user_to_create_a_new_topic()
    {
        $userId = $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicToevoeg.php',
            ['forumid' => $this->forumId],
            [
                'titel' => 'Random topic',
                'reactie' => 'With some random text'
            ]
        );

        $this->assertDatabaseHas('forum_forums', [
            'forum_id' => $this->forumId,
            'last_post' => $userId
        ]);

        $this->assertDatabaseHas('forum_topics', [
            'forum_id' => $this->forumId,
            'topic_titel' => 'Random topic',
            'topic_poster' => $userId,
            'last_post' => $userId,
            'topic_views' => 0
        ]);

        $this->assertDatabaseHas('forum_posts', [
            'post_poster' => $userId,
            'post_titel' => 'Random topic',
            'post_text' => 'With some random text'
        ]);

        $this->assertDatabaseHas('users', [
            'userid' => $userId,
            'posts' => 1
        ]);

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_redirects_to_login_page_if_user_isnt_logged_in()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicToevoeg.php',
            ['forumid' => $this->forumId],
            [
                'titel' => 'Random topic',
                'reactie' => 'With some random text'
            ]
        );

        $this->assertDatabaseMissing('forum_topics', [
            'forum_id' => $this->forumId,
            'topic_titel' => 'Random topic',
            'topic_views' => 0
        ]);

        $this->assertDatabaseMissing('forum_posts', [
            'post_titel' => 'Random topic',
            'post_text' => 'With some random text'
        ]);

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_forum_id_get_parameter_is_missing()
    {
        $userId = $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicToevoeg.php',
            [],
            [
                'titel' => 'Random topic',
                'reactie' => 'With some random text'
            ]
        );

        $this->assertDatabaseMissing('forum_topics', [
            'topic_titel' => 'Random topic',
            'topic_poster' => $userId,
            'last_post' => $userId,
            'topic_views' => 0
        ]);

        $this->assertDatabaseMissing('forum_posts', [
            'post_poster' => $userId,
            'post_titel' => 'Random topic',
            'post_text' => 'With some random text'
        ]);

        $this->assertDatabaseMissing('users', [
            'userid' => $userId,
            'posts' => 1
        ]);

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_titel_post_parameter_is_missing()
    {
        $userId = $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicToevoeg.php',
            ['forumid' => $this->forumId],
            [
                'reactie' => 'With some random text'
            ]
        );

        $this->assertDatabaseMissing('forum_forums', [
            'forum_id' => $this->forumId,
            'last_post' => $userId
        ]);

        $this->assertDatabaseMissing('forum_topics', [
            'forum_id' => $this->forumId,
            'topic_poster' => $userId,
            'last_post' => $userId,
            'topic_views' => 0
        ]);

        $this->assertDatabaseMissing('forum_posts', [
            'post_poster' => $userId,
            'post_text' => 'With some random text'
        ]);

        $this->assertDatabaseHas('users', [
            'userid' => $userId,
            'posts' => 0
        ]);

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_reactie_post_parameter_is_missing()
    {
        $userId = $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicToevoeg.php',
            ['forumid' => $this->forumId],
            [
                'titel' => 'Random title'
            ]
        );

        $this->assertDatabaseMissing('forum_forums', [
            'forum_id' => $this->forumId,
            'last_post' => $userId
        ]);

        $this->assertDatabaseMissing('forum_topics', [
            'forum_id' => $this->forumId,
            'forum_titel' => 'Random title',
            'topic_poster' => $userId,
            'last_post' => $userId,
            'topic_views' => 0
        ]);

        $this->assertDatabaseMissing('forum_posts', [
            'post_poster' => $userId,
            'post_titel' => 'Random title'
        ]);

        $this->assertDatabaseHas('users', [
            'userid' => $userId,
            'posts' => 0
        ]);

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_forum_doesnt_exist()
    {
        $userId = $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../forum/topicToevoeg.php',
            ['forumid' => 999],
            [
                'titel' => 'Random topic',
                'reactie' => 'With some random text'
            ]
        );

        $this->assertDatabaseMissing('forum_forums', [
            'forum_id' => 999,
            'last_post' => $userId
        ]);

        $this->assertDatabaseMissing('forum_topics', [
            'forum_id' => 999,
            'topic_titel' => 'Random topic',
            'topic_poster' => $userId,
            'last_post' => $userId,
            'topic_views' => 0
        ]);

        $this->assertDatabaseMissing('forum_posts', [
            'post_poster' => $userId,
            'post_titel' => 'Random topic',
            'post_text' => 'With some random text'
        ]);

        $this->assertDatabaseHas('users', [
            'userid' => $userId,
            'posts' => 0
        ]);

        $this->assertEquals('', $page);
    }
}
