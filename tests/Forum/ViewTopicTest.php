<?php

namespace Tests\Forum;

use Tests\Factories\ForumCategoryFactory;
use Tests\Factories\ForumFactory;
use Tests\Factories\ForumPostFactory;
use Tests\Factories\ForumTopicFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class ViewTopicTest extends TestCase
{
    private $topicId;
    private $userId;
    private $postId;

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

        $this->userId = UserFactory::create(
            self::$pdo,
            'Yogi',
            'secret',
            'yogi@bear.com',
            '127.0.0.1'
        );

        $categoryId = ForumCategoryFactory::create(self::$pdo, 'Everything', 0);

        $forumId = ForumFactory::create(
            self::$pdo,
            $categoryId,
            'General',
            'General forum',
            $this->userId
        );

        $this->topicId = ForumTopicFactory::create(
            self::$pdo,
            $forumId,
            'Nice topic',
            $this->userId
        );

        foreach(range(1, 20) as $i) {
            ForumPostFactory::create(
                self::$pdo,
                $this->topicId,
                $this->userId,
                'Title ' . $i,
                'Body ' . $i
            );
        }

        $this->postId = ForumPostFactory::create(
            self::$pdo,
            $this->topicId,
            $this->userId,
            'Blabla',
            'Lorem Ipsum'
        );
    }

    /** @test */
    public function it_shows_first_20_posts_on_the_first_page()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../forum/viewTopic.php',
            [
                'topicid' => $this->topicId,
                'p' => 0
            ]
        );

        $this->assertContains('Nice topic', $page);
        $this->assertContainsInOrder([
            'Title 1', 'Body 1',
            'Title 19', 'Body 19',
        ], $page);
        $this->assertNotContains('Blabla', $page);
        $this->assertNotContains('Lorem Ipsum', $page);

        $this->assertContains('Volgende', $page);
        $this->assertNotContains('Vorige', $page);
    }

    /** @test */
    public function it_shows_post_21_on_the_next_page()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../forum/viewTopic.php',
            [
                'topicid' => $this->topicId,
                'p' => 1
            ]
        );

        $this->assertContains('Nice topic', $page);
        $this->assertContainsInOrder([
            'Blabla', 'Lorem Ipsum'
        ],$page);

        $this->assertNotContains('Title 1', $page);
        $this->assertNotContains('Title 20', $page);
        $this->assertNotContains('Body 1', $page);
        $this->assertNotContains('Body 20', $page);

        $this->assertNotContains('Volgende', $page);
        $this->assertContains('Vorige', $page);
    }

    /** @test */
    public function it_shows_edit_and_delete_buttons_when_logged_in_user_is_owner_of_the_post()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/viewTopic.php',
            [
                'topicid' => $this->topicId,
                'p' => 1
            ]
        );

        $this->assertContains('postBewerk.php?postid=' . $this->postId, $page);
        $this->assertContains('postVerwijder.php?postid=' . $this->postId, $page);
    }

    /** @test */
    public function it_shows_edit_and_delete_buttons_when_logged_in_user_is_forum_moderator()
    {
        $this->login(null, Permissions::MANAGE_COMMENTS);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/viewTopic.php',
            [
                'topicid' => $this->topicId,
                'p' => 1
            ]
        );

        $this->assertContains('postBewerk.php?postid=' . $this->postId, $page);
        $this->assertContains('postVerwijder.php?postid=' . $this->postId, $page);
    }

    /** @test */
    public function it_doesnt_show_edit_and_delete_buttons_when_logged_in_user_doesnt_have_permissions()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../forum/viewTopic.php',
            [
                'topicid' => $this->topicId,
                'p' => 1
            ]
        );

        $this->assertNotContains('postBewerk.php?postid=' . $this->postId, $page);
        $this->assertNotContains('postVerwijder.php?postid=' . $this->postId, $page);
    }

    /** @test */
    public function it_doesnt_show_edit_and_delete_buttons_when_user_isnt_logged_in()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../forum/viewTopic.php',
            [
                'topicid' => $this->topicId,
                'p' => 1
            ]
        );

        $this->assertNotContains('postBewerk.php?postid=' . $this->postId, $page);
        $this->assertNotContains('postVerwijder.php?postid=' . $this->postId, $page);
    }

    /** @test */
    public function it_shows_404_when_forum_id_parameter_is_missing()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../forum/viewTopic.php',
            [
                'p' => 1
            ]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_p_parameter_is_missing()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../forum/viewTopic.php',
            [
                'topicid' => $this->topicId,
            ]
        );

        $this->assertEquals('', $page);
    }
}
