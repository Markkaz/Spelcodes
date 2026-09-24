<?php
namespace Tests\Admin;

use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class AddLinkTest extends TestCase {
    public static function getTables()
    {
        return [
            'users',
            'links',
        ];
    }

    /** @test */
    public function it_shows_the_add_link_form() {
        $this->login(null, Permissions::MANAGE_LINKS);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/linkToevoeg.php'
        );

        $this->assertContains('Link toevoegen', $page);
    }

    /** @test */
    public function it_adds_a_link() {
        $this->login(null, Permissions::MANAGE_LINKS);

        $this->visitPage(
            __DIR__ . '/../../admin/linkToevoeg.php',
            [],
            [
                'link' => 'Microsoft',
                'url' => 'https://microsoft.com',
            ]
        );

        $this->assertDatabaseHas('links', [
            'link' => 'Microsoft',
            'url' => 'https://microsoft.com',
            'incomming' => 0,
            'outcomming' => 0,
        ]);
    }

    /** @test */
    public function it_redirects_when_user_is_unauthenticated() {
        $page = $this->visitPage(
            __DIR__ . '/../../admin/linkToevoeg.php',
            [],
            [
                'link' => 'Microsoft',
                'url' => 'https://microsoft.com',
            ]
        );

        $this->assertDatabaseMissing('links', [
            'link' => 'Microsoft',
            'url' => 'https://microsoft.com',
        ]);
        $this->assertEquals("", $page);
    }

    /** @test */
    public function it_gives_an_error_when_user_is_unauthorized() {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../admin/linkToevoeg.php',
            [],
            [
                'link' => 'Microsoft',
                'url' => 'https://microsoft.com',
            ]
        );

        $this->assertDatabaseMissing('links', [
            'link' => 'Microsoft',
            'url' => 'https://microsoft.com',
        ]);
        $this->assertEquals("Geen permissie...", $page);
    }
}