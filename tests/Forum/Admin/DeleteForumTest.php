<?php

use Tests\Factories\ForumCategoryFactory;
use Tests\Factories\ForumFactory;
use Tests\Factories\ForumTopicFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class DeleteForumTest extends TestCase {
    protected $catId;
    protected $forumId;

    public static function getTables() {
        return [
            'users',
            'forum_categories',
            'forum_forums',
            'forum_topics',
        ];
    }

    public function setUp() {
        parent::setUp();

        $this->catId = ForumCategoryFactory::create(
            self::$pdo,
            'Consoles',
            1
        );

        $this->forumId = ForumFactory::create(
            self::$pdo,
            $this->catId,
            'Xbox',
            'Discussions about Xbox'
        );
    }

    /** @test */
    public function it_shows_the_delete_a_forum_form() {
        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/forumVerwijder.php',
            ['forumid' => $this->forumId]
        );

        $this->assertContains("Forum verwijderen", $page);
        $this->assertDatabaseHas('forum_forums', [
            'cat_id' => $this->catId,
            'forum_titel' => 'Xbox',
            'forum_text' => 'Discussions about Xbox',
        ]);
    }

    /** @test */
    public function it_deletes_a_forum() {
        $this->login(null, Permissions::FORUM_ADMIN);

        $this->visitPage(
            __DIR__ . '/../../../forum/Admin/forumVerwijder.php',
            ['forumid' => $this->forumId],
            ['delete' => 1]
        );

        $this->assertDatabaseMissing('forum_forums', [
            'cat_id' => $this->catId,
            'forum_titel' => 'Xbox',
            'forum_text' => 'Discussions about Xbox',
        ]);
    }

    /** @test */
    public function it_shows_an_error_when_forum_id_is_missing() {
        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/forumVerwijder.php',
            [],
            ['delete' => 1]
        );

        $this->assertDatabaseHas('forum_forums', [
            'cat_id' => $this->catId,
            'forum_titel' => 'Xbox',
            'forum_text' => 'Discussions about Xbox',
        ]);
        $this->assertEquals("Geen forumid...", $page);
    }

    /** @test */
    public function it_redirects_when_user_is_not_logged_in()
    {
        $this->visitPage(
            __DIR__ . '/../../../forum/Admin/forumVerwijder.php',
            ['forumid' => $this->forumId],
            ['delete' => 1]
        );

        $this->assertDatabaseHas('forum_forums', [
            'cat_id' => $this->catId,
            'forum_titel' => 'Xbox',
            'forum_text' => 'Discussions about Xbox',
        ]);
    }

    /** @test */
    public function it_shows_an_error_when_user_does_not_have_permissions() {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/forumVerwijder.php',
            ['forumid' => $this->forumId],
            ['delete' => 1]
        );

        $this->assertDatabaseHas('forum_forums', [
            'cat_id' => $this->catId,
            'forum_titel' => 'Xbox',
            'forum_text' => 'Discussions about Xbox',
        ]);
        $this->assertEquals("Geen permissie...", $page);
    }

    /** @test */
    public function it_shows_an_error_when_forum_contains_topics()
    {
        $userId = $this->login(null, Permissions::FORUM_ADMIN);

        ForumTopicFactory::create(
            self::$pdo,
            $this->forumId,
            'Question about Xbox 360',
            $userId
        );

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/forumVerwijder.php',
            ['forumid' => $this->forumId],
            ['delete' => 1]
        );

        $this->assertDatabaseHas('forum_forums', [
            'cat_id' => $this->catId,
            'forum_titel' => 'Xbox',
            'forum_text' => 'Discussions about Xbox',
        ]);
        $this->assertContains("Kon forum niet verwijderen", $page);
    }
}