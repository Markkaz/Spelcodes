<?php

namespace Tests\Forum;

use Tests\Factories\ForumCategoryFactory;
use Tests\Factories\ForumFactory;
use Tests\Factories\ForumPostFactory;
use Tests\Factories\ForumTopicFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class EditPostTest extends TestCase
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
    public function it_shows_edit_form_to_owner_of_the_post()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/postBewerk.php',
            ['postid' => $this->postId]
        );

        $this->assertContains('A random reply', $page);
        $this->assertContains('With some random argument', $page);
    }

    /** @test */
    public function it_shows_edit_form_to_forum_moderator()
    {
        $this->login(null, Permissions::MANAGE_COMMENTS);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/postBewerk.php',
            ['postid' => $this->postId]
        );

        $this->assertContains('A random reply', $page);
        $this->assertContains('With some random argument', $page);
    }

    /** @test */
    public function it_allows_post_owner_to_edit_their_post()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/postBewerk.php',
            ['postid' => $this->postId],
            [
                'titel' => 'Edited title',
                'reactie' => 'Edited body'
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('forum_posts', [
            'post_id' => $this->postId,
            'post_titel' => 'Edited title',
            'post_text' => 'Edited body'
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_allows_forum_moderator_to_edit_their_post()
    {
        $this->login(null, Permissions::MANAGE_COMMENTS);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/postBewerk.php',
            ['postid' => $this->postId],
            [
                'titel' => 'Edited title',
                'reactie' => 'Edited body'
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('forum_posts', [
            'post_id' => $this->postId,
            'post_titel' => 'Edited title',
            'post_text' => 'Edited body'
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_post_id_parameter_is_missing()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/postBewerk.php'
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_redirects_login_form_when_user_isnt_logged_in()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../forum/postBewerk.php',
            ['postid' => $this->postId]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_user_hasnt_permission_to_edit_post()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../forum/postBewerk.php',
            ['postid' => $this->postId],
            [
                'titel' => 'Edited title',
                'reactie' => 'Edited body'
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('forum_posts', [
            'post_id' => $this->postId,
            'post_titel' => 'A random reply',
            'post_text' => 'With some random argument'
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_post_doesnt_exist()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/postBewerk.php',
            ['postid' => 999]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_only_allows_editing_when_all_post_parameters_are_there()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/postBewerk.php',
            ['postid' => $this->postId],
            [
                'titel' => 'Edited title'
            ]
        );

        $this->assertContains('A random reply', $page);
        $this->assertContains('With some random argument', $page);
        $this->assertDatabaseHas('forum_posts', [
            'post_id' => $this->postId,
            'post_titel' => 'A random reply',
            'post_text' => 'With some random argument'
        ]);
    }
}
