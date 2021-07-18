<?php

namespace Tests\Forum\Admin;

use Tests\Factories\ForumCategoryFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class EditCategoryTest extends TestCase
{
    private $categoryId;

    public static function getTables()
    {
        return [
            'users',
            'forum_categories',
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->categoryId = ForumCategoryFactory::create(self::$pdo, 'General', 0);
    }

    /** @test */
    public function it_shows_edit_form_to_forum_admin()
    {
        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/catBewerk.php',
            ['catid' => $this->categoryId]
        );

        $this->assertContains('General', $page);
    }

    /** @test */
    public function it_edits_category_when_user_is_forum_admin()
    {
        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/catBewerk.php',
            ['catid' => $this->categoryId],
            [
                'titel' => 'Games',
                'order' => 5
            ]
        );

        $this->assertDatabaseHas('forum_categories', [
            'cat_id' => $this->categoryId,
            'cat_titel' => 'Games',
            'cat_order' => 5
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_cat_id_get_parameter_is_missing()
    {
        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/catBewerk.php'
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_redirects_guests_to_login_form()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/catBewerk.php',
            ['catid' => $this->categoryId],
            [
                'titel' => 'Games',
                'order' => 5
            ]
        );

        $this->assertDatabaseHas('forum_categories', [
            'cat_id' => $this->categoryId,
            'cat_titel' => 'General',
            'cat_order' => 0
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_for_unauthorised_users()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/catBewerk.php',
            ['catid' => $this->categoryId],
            [
                'titel' => 'Games',
                'order' => 5
            ]
        );

        $this->assertDatabaseHas('forum_categories', [
            'cat_id' => $this->categoryId,
            'cat_titel' => 'General',
            'cat_order' => 0
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_titel_post_parameter_is_missing()
    {
        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/catBewerk.php',
            ['catid' => $this->categoryId],
            [
                'order' => 5
            ]
        );

        $this->assertDatabaseHas('forum_categories', [
            'cat_id' => $this->categoryId,
            'cat_titel' => 'General',
            'cat_order' => 0
        ]);
        $this->assertContains('General', $page);
    }

    /** @test */
    public function it_shows_404_when_order_post_parameter_is_missing()
    {
        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/catBewerk.php',
            ['catid' => $this->categoryId],
            [
                'titel' => 'Games'
            ]
        );

        $this->assertDatabaseHas('forum_categories', [
            'cat_id' => $this->categoryId,
            'cat_titel' => 'General',
            'cat_order' => 0
        ]);
        $this->assertContains('General', $page);
    }

    /** @test */
    public function it_shows_404_when_category_doesnt_exist()
    {
        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/catBewerk.php',
            ['catid' => 999]
        );

        $this->assertEquals('', $page);
    }
}
