<?php

namespace Tests\Forum;

use Tests\Factories\ForumCategoryFactory;
use Tests\Factories\ForumFactory;
use Tests\Factories\ForumTopicFactory;
use Tests\Factories\TopicFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;

class AddPostTest extends TestCase
{
    private $topicId;

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

        $userId = UserFactory::create(
            self::$pdo,
            'Yogi',
            'secret',
            'yogi@bear.com',
            '127.0.0.1'
        );

        $categoryId = ForumCategoryFactory::create(self::$pdo, 'General', 0);

        $forumId = ForumFactory::create(
            self::$pdo,
            $categoryId,
            'General discussions',
            'General discussions'
        );

        $this->topicId = ForumTopicFactory::create(
            self::$pdo,
            $forumId,
            'This game sucks!',
            $userId
        );
    }


    /** @test */
    public function it_adds_a_new_post_when_user_is_logged_in()
    {
        $userId = $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../forum/postToevoeg.php',
            ['topicid' => $this->topicId],
            [
                'titel' => 'Disagree',
                'reactie' => 'I disagree!'
            ]
        );

        $this->assertDatabaseHas('forum_posts', [
            'topic_id' => $this->topicId,
            'post_titel' => 'Disagree',
            'post_text' => 'I disagree!'
        ]);
        $this->assertDatabaseHas('forum_forums', [
            'forum_titel' => 'General discussions',
            'last_post' => $userId
        ]);
        $this->assertDatabaseHas('forum_topics', [
            'topic_titel' => 'This game sucks!',
            'last_post' => $userId
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_redirects_to_login_page_when_user_isnt_logged_in()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../forum/postToevoeg.php',
            ['topicid' => $this->topicId],
            [
                'titel' => 'Disagree',
                'reactie' => 'I disagree!'
            ]
        );

        $this->assertDatabaseMissing('forum_posts', [
            'topic_id' => $this->topicId,
            'post_titel' => 'Disagree',
            'post_text' => 'I disagree!'
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_topic_id_parameter_is_missing()
    {
        $userId = $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../forum/postToevoeg.php',
            [],
            [
                'titel' => 'Disagree',
                'reactie' => 'I disagree!'
            ]
        );

        $this->assertDatabaseMissing('forum_posts', [
            'topic_id' => $this->topicId,
            'post_titel' => 'Disagree',
            'post_text' => 'I disagree!'
        ]);
        $this->assertDatabaseHas('forum_forums', [
            'forum_titel' => 'General discussions',
            'last_post' => 0
        ]);
        $this->assertDatabaseHas('forum_topics', [
            'topic_titel' => 'This game sucks!',
            'last_post' => 0
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_topic_doesnt_exist()
    {
        $userId = $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../forum/postToevoeg.php',
            ['topicid' => 999],
            [
                'titel' => 'Disagree',
                'reactie' => 'I disagree!'
            ]
        );

        $this->assertDatabaseMissing('forum_posts', [
            'post_titel' => 'Disagree',
            'post_text' => 'I disagree!'
        ]);
        $this->assertDatabaseMissing('forum_forums', [
            'forum_titel' => 'General discussions',
            'last_post' => $userId
        ]);
        $this->assertDatabaseMissing('forum_topics', [
            'topic_titel' => 'This game sucks!',
            'last_post' => $userId
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_post_parameters_are_missing()
    {
        $userId = $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../forum/postToevoeg.php',
            ['topicid' => $this->topicId]
        );

        $this->assertDatabaseMissing('forum_posts', [
            'post_titel' => '',
            'post_text' => ''
        ]);
        $this->assertDatabaseMissing('forum_forums', [
            'forum_titel' => 'General discussions',
            'last_post' => $userId
        ]);
        $this->assertDatabaseMissing('forum_topics', [
            'topic_titel' => 'This game sucks!',
            'last_post' => $userId
        ]);
        $this->assertEquals('', $page);
    }
}
