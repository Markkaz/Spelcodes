<?php

namespace Tests\Forum;

use Tests\Factories\ForumCategoryFactory;
use Tests\Factories\ForumFactory;
use Tests\Factories\ForumPostFactory;
use Tests\Factories\ForumTopicFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class DeletePostTest extends TestCase
{
    private $userId;
    private $postId;

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

        $topicId = ForumTopicFactory::create(
            self::$pdo,
            $forumId,
            'Interesting topic',
            $this->userId
        );

        $this->postId = ForumPostFactory::create(
            self::$pdo,
            $topicId,
            $this->userId,
            'A random reply',
            'With some random argument'
        );
    }

    /** @test */
    public function it_shows_delete_form_to_post_owner()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/postVerwijder.php',
            ['postid' => $this->postId]
        );

        $this->assertContains('<b>' . $this->postId . '</b>', $page);
    }

    /** @test */
    public function it_shows_delete_form_to_forum_moderator()
    {
        $this->login(null, Permissions::FORUM_MODERATOR);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/postVerwijder.php',
            ['postid' => $this->postId]
        );

        $this->assertContains('<b>' . $this->postId . '</b>', $page);
    }

    /** @test */
    public function it_allows_post_owner_to_delete_their_post()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/postVerwijder.php',
            ['postid' => $this->postId],
            ['delete' => true]
        );

        $this->assertDatabaseMissing('forum_posts', [
            'post_id' => $this->postId,
            'post_titel' => 'A random reply',
            'post_text' => 'With some random argument'
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_allows_forum_moderator_to_delete_post()
    {
        $this->login(null, Permissions::FORUM_MODERATOR);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/postVerwijder.php',
            ['postid' => $this->postId],
            ['delete' => true]
        );

        $this->assertDatabaseMissing('forum_posts', [
            'post_id' => $this->postId,
            'post_titel' => 'A random reply',
            'post_text' => 'With some random argument'
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_postid_parameter_is_missing()
    {
        $this->login(null, Permissions::FORUM_MODERATOR);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/postVerwijder.php'
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_post_doesnt_exist()
    {
        $this->login(null, Permissions::FORUM_MODERATOR);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/postVerwijder.php',
            ['postid' => 999]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_redirects_user_to_login_form_when_they_arent_logged_in()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../forum/postVerwijder.php',
            ['postid' => $this->postId],
            ['delete' => true]
        );

        $this->assertDatabaseHas('forum_posts', [
            'post_id' => $this->postId,
            'post_titel' => 'A random reply',
            'post_text' => 'With some random argument'
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_user_doesnt_have_permission()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../forum/postVerwijder.php',
            ['postid' => $this->postId],
            ['delete' => true]
        );

        $this->assertDatabaseHas('forum_posts', [
            'post_id' => $this->postId,
            'post_titel' => 'A random reply',
            'post_text' => 'With some random argument'
        ]);
        $this->assertEquals('', $page);
    }
}
