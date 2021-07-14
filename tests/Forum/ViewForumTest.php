<?php

namespace Tests\Forum;

use Tests\Factories\ForumCategoryFactory;
use Tests\Factories\ForumFactory;
use Tests\Factories\ForumTopicFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class ViewForumTest extends TestCase
{
    private $userId;
    private $forumId;
    private $topicId;

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

        $this->forumId = ForumFactory::create(
            self::$pdo,
            $categoryId,
            'Website updates',
            'Updates about the website'
        );

        $invisibleForumId = ForumFactory::create(
            self::$pdo,
            $categoryId,
            'Game chat',
            'Chat about games'
        );

        $this->topicId = ForumTopicFactory::create(
            self::$pdo,
            $this->forumId,
            'Visible topic',
            $this->userId
        );

        ForumTopicFactory::create(
            self::$pdo,
            $invisibleForumId,
            'Invisible topic',
            $this->userId
        );
    }


    public static function getTables()
    {
        return [
            'users',
            'forum_categories',
            'forum_forums',
            'forum_topics',
        ];
    }

    /** @test */
    public function it_shows_topics_in_a_forum()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../forum/viewForum.php',
            ['forumid' => $this->forumId]
        );

        $this->assertContains('Visible topic', $page);
        $this->assertContains('Yogi', $page);
        $this->assertNotContains('Invisible topic', $page);

        $this->assertNotContains('topicBewerk.php?topicid=' . $this->topicId, $page);
        $this->assertNotContains('topicVerwijder.php?topicid=' . $this->topicId, $page);
        $this->assertNotContains('topicMove.php?topicid=' . $this->topicId . '&forumid=' . $this->forumId, $page);
    }

    /** @test */
    public function it_shows_topic_edit_and_delete_buttons_for_topic_owner()
    {
        $this->login($this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/viewForum.php',
            ['forumid' => $this->forumId]
        );

        $this->assertContains('topicBewerk.php?topicid=' . $this->topicId, $page);
        $this->assertContains('topicVerwijder.php?topicid=' . $this->topicId, $page);
        $this->assertNotContains('topicMove.php?topicid=' . $this->topicId . '&forumid=' . $this->forumId, $page);
    }

    /** @test */
    public function it_shows_topic_edit_delete_and_move_buttons_for_forum_moderator()
    {
        $this->login(null, Permissions::MANAGE_COMMENTS);

        $page = $this->visitPage(
            __DIR__ . '/../../forum/viewForum.php',
            ['forumid' => $this->forumId]
        );

        $this->assertContains('topicBewerk.php?topicid=' . $this->topicId, $page);
        $this->assertContains('topicVerwijder.php?topicid=' . $this->topicId, $page);
        $this->assertContains('topicMove.php?topicid=' . $this->topicId . '&forumid=' . $this->forumId, $page);
    }

    /** @test */
    public function it_shows_404_when_forum_id_parameter_is_missing()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../forum/viewForum.php'
        );

        $this->assertEquals('', $page);
    }
}
