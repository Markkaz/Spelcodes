<?php

namespace Tests\Forum\Admin;

use Tests\Factories\ForumCategoryFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class AddForumTest extends TestCase {
    private $catId;

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

        $this->catId = ForumCategoryFactory::create(self::$pdo, 'Consoles', 0);
    }

    /** @test */
    public function it_adds_a_new_forum() {
        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/forumToevoeg.php',
            [],
            [
                'titel' => 'Xbox',
                'beschrijving' => 'Discussions about the Xbox',
                'catid' => $this->catId,
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('forum_forums', [
            'cat_id' => $this->catId,
            'forum_titel' => 'Xbox',
            'forum_text' => 'Discussions about the Xbox',
        ]);
    }

    /** @test */
    public function it_redirects_when_not_logged_in() {
        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/forumToevoeg.php',
            [],
            [
                'titel' => 'Xbox',
                'beschrijving' => 'Discussions about the Xbox',
                'catid' => $this->catId,
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseMissing('forum_forums', [
            'cat_id' => $this->catId,
            'forum_titel' => 'Xbox',
            'forum_text' => 'Discussions about the Xbox',
        ]);
    }

    /** @test */
    public function it_dies_when_not_correct_permissions() {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/forumToevoeg.php',
            [],
            [
                'titel' => 'Xbox',
                'beschrijving' => 'Discussions about the Xbox',
                'catid' => $this->catId,
            ]
        );

        $this->assertDatabaseMissing('forum_forums', [
            'cat_id' => $this->catId,
            'forum_titel' => 'Xbox',
            'forum_text' => 'Discussions about the Xbox',
        ]);
        $this->assertEquals('Geen permissie...', $page);
    }

    /** @test */
    public function it_dies_when_category_is_missing() {
        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/forumToevoeg.php',
            [],
            [
                'titel' => 'Xbox',
                'beschrijving' => 'Discussions about the Xbox',
            ]
        );

        $this->assertDatabaseMissing('forum_forums', [
            'forum_titel' => 'Xbox',
            'forum_text' => 'Discussions about the Xbox',
        ]);
        $this->assertEquals('Geen permissie...', $page);
    }

    /** @test */
    public function it_dies_when_titel_is_missing()
    {
        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/forumToevoeg.php',
            [],
            [
                'catid' => $this->catId,
                'beschrijving' => 'Discussions about the Xbox',
            ]
        );

        $this->assertDatabaseMissing('forum_forums', [
            'cat_id' => $this->catId,
            'forum_text' => 'Discussions about the Xbox',
        ]);
        $this->assertEquals('Geen permissie...', $page);
    }

    public function it_dies_when_beschrijving_is_missing() {
        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/forumToevoeg.php',
            [],
            [
                'catid' => $this->catId,
                'titel' => 'Xbox',
            ]
        );

        $this->assertDatabaseMissing('forum_forums', [
            'cat_id' => $this->catId,
            'forum_titel' => 'Xbox',
        ]);
        $this->assertEquals('Geen permissie...', $page);
    }
}