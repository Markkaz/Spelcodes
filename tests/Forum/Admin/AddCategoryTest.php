<?php

namespace Tests\Forum\Admin;

use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class AddCategoryTest extends TestCase
{
    public static function getTables()
    {
        return [
            'users',
            'forum_categories',
        ];
    }

    /** @test */
    public function it_adds_a_forum_category()
    {
        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/catToevoeg.php',
            [],
            [
                'titel' => 'General',
                'order' => 1
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('forum_categories', [
            'cat_titel' => 'General',
            'cat_order' => 1
        ]);
    }

    /** @test */
    public function it_shows_404_when_titel_post_parameter_is_missing()
    {
        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/catToevoeg.php',
            [],
            [
                'order' => 1
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseMissing('forum_categories', [
            'cat_titel' => 'General',
            'cat_order' => 1
        ]);
    }

    /** @test */
    public function it_shows_404_when_order_post_parameter_is_missing()
    {
        $this->login(null, Permissions::FORUM_ADMIN);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/catToevoeg.php',
            [],
            [
                'titel' => 'General'
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseMissing('forum_categories', [
            'cat_titel' => 'General',
            'cat_order' => 1
        ]);
    }

    /** @test */
    public function it_redirects_guests_to_login_form()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/catToevoeg.php',
            [],
            [
                'titel' => 'General',
                'order' => 1
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseMissing('forum_categories', [
            'cat_titel' => 'General',
            'cat_order' => 1
        ]);
    }

    /** @test */
    public function it_shows_404_for_unauthorised_users()
    {
        $this->login(null);

        $page = $this->visitPage(
            __DIR__ . '/../../../forum/Admin/catToevoeg.php',
            [],
            [
                'titel' => 'General',
                'order' => 1
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseMissing('forum_categories', [
            'cat_titel' => 'General',
            'cat_order' => 1
        ]);
    }
}
