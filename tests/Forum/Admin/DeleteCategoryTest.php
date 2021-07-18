<?php

namespace Tests\Forum\Admin;

use Tests\Factories\ForumCategoryFactory;
use Tests\Factories\ForumFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class DeleteCategoryTest extends TestCase
{
    private $categoryId;

    public static function getTables()
    {
        return [
            'users',
            'forum_categories',
            'forum_forums',
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->categoryId = ForumCategoryFactory::create(self::$pdo, 'General', 1);
    }

    /** @test */
    public function it_shows_delete_form_to_forum_admin()
    {
        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/catVerwijder.php',
            ['catid' => $this->categoryId]
        );

        $this->assertContains('Categorie verwijderen', $page);
    }

    /** @test */
    public function it_deletes_category_when_user_is_forum_admin()
    {
        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/catVerwijder.php',
            ['catid' => $this->categoryId],
            ['delete' => true]
        );

        $this->assertDatabaseMissing('forum_categories', [
            'cat_id' => $this->categoryId,
            'cat_titel' => 'General',
            'cat_order' => 1
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_error_when_trying_to_delete_a_category_with_attached_fora()
    {
        ForumFactory::create(
            self::$pdo,
            $this->categoryId,
            'Just chatting',
            'About nothing in particular'
        );

        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/catVerwijder.php',
            ['catid' => $this->categoryId],
            ['delete' => true]
        );

        $this->assertDatabaseHas('forum_categories', [
            'cat_id' => $this->categoryId,
            'cat_titel' => 'General',
            'cat_order' => 1
        ]);
        $this->assertContains('Kon categorie niet verwijderen', $page);
    }

    /** @test */
    public function shows_404_when_catid_get_parameter_is_missing()
    {
        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/catVerwijder.php'
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_redirects_guests_to_login_form()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/catVerwijder.php',
            ['catid' => $this->categoryId],
            ['delete' => true]
        );

        $this->assertDatabaseHas('forum_categories', [
            'cat_id' => $this->categoryId,
            'cat_titel' => 'General',
            'cat_order' => 1
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_for_unauthorised_users()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/catVerwijder.php',
            ['catid' => $this->categoryId],
            ['delete' => true]
        );

        $this->assertDatabaseHas('forum_categories', [
            'cat_id' => $this->categoryId,
            'cat_titel' => 'General',
            'cat_order' => 1
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_category_doesnt_exist()
    {
        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/catVerwijder.php',
            ['catid' => 999]
        );
        $this->assertEquals('', $page);
    }
}
