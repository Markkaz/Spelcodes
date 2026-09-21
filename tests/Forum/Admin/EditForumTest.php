<?php

use Tests\Factories\ForumCategoryFactory;
use Tests\Factories\ForumFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class ForumTest extends TestCase {
    private $catId;
    private $forumId;

    public static function getTables()
    {
        return [
            'users',
            'forum_categories',
            'forum_forums',
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
            'Discussions around the Xbox'
        );
    }

    /** @test */
    public function it_shows_the_edit_forum_form_when_opening_the_page() {
        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/forumBewerk.php',
            [
                'forumid' => $this->forumId
            ]
        );

        $this->assertContains("Forum bewerken", $page);
        $this->assertContains("Xbox", $page);
        $this->assertContains("Discussions around the Xbox", $page);
        $this->assertContains("Bewerk", $page);
    }

    /** @test */
    public function it_edits_a_forum_when_submitting_the_form() {
        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/forumBewerk.php',
            [
                'forumid' => $this->forumId
            ],
            [
                'catid' => $this->catId,
                'titel' => 'Playstation',
                'beschrijving' => 'Discussions around the Playstation',
            ]
        );

        $this->assertDatabaseHas("forum_forums", [
            'cat_id' => $this->catId,
            'forum_titel' => 'Playstation',
            'forum_text' => 'Discussions around the Playstation',
        ]);
    }

    /** @test */
    public function it_gives_an_error_when_forumid_is_missing() {
        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/forumBewerk.php',
            [],
            [
                'catid' => $this->catId,
                'titel' => 'Playstation',
                'beschrijving' => 'Discussions around the Playstation',
            ]
        );

        $this->assertEquals("Geen forumid...", $page);
        $this->assertDatabaseHas("forum_forums", [
            'cat_id' => $this->catId,
            'forum_titel' => 'Xbox',
            'forum_text' => 'Discussions around the Xbox',
        ]);
    }

    /** @test */
    public function it_redirects_when_user_is_not_logged_in()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/forumBewerk.php',
            [
                'forumid' => $this->forumId
            ],
            [
                'catid' => $this->catId,
                'titel' => 'Playstation',
                'beschrijving' => 'Discussions around the Playstation',
            ]
        );

        $this->assertDatabaseHas("forum_forums", [
            'cat_id' => $this->catId,
            'forum_titel' => 'Xbox',
            'forum_text' => 'Discussions around the Xbox',
        ]);
        $this->assertEquals("", $page);
    }

    /** @test */
    public function it_gives_an_error_when_user_does_not_have_permission() {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/forumBewerk.php',
            [
                'forumid' => $this->forumId
            ],
            [
                'catid' => $this->catId,
                'titel' => 'Playstation',
                'beschrijving' => 'Discussions around the Playstation',
            ]
        );

        $this->assertDatabaseHas("forum_forums", [
            'cat_id' => $this->catId,
            'forum_titel' => 'Xbox',
            'forum_text' => 'Discussions around the Xbox',
        ]);
        $this->assertEquals("Geen permissie...", $page);
    }
}