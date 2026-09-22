<?php
namespace Tests\Admin;

use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class AddConsoleTest extends TestCase {
    public static function getTables() {
        return [
            'users',
            'consoles',
        ];
    }

    /** @test */
    public function it_shows_add_console_form() {
        $this->login(null, Permissions::MANAGE_CONSOLES);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/consoleToevoeg.php'
        );

        $this->assertContains("Console toevoegen", $page);
    }

    /** @test */
    public function it_adds_a_new_console() {
        $this->login(null, Permissions::MANAGE_CONSOLES);

        $this->visitPage(
            __DIR__ . '/../../admin/consoleToevoeg.php',
            [],
            [
                'naam' => 'Xbox'
            ]
        );

        $this->assertDatabaseHas('consoles', [
            'naam' => 'Xbox'
        ]);
    }

    /** @test */
    public function it_redirects_when_user_is_unauthenticated() {
        $page = $this->visitPage(
            __DIR__ . '/../../admin/consoleToevoeg.php',
            [],
            [
                'naam' => 'Xbox'
            ]
        );

        $this->assertDatabaseMissing('consoles', [
            'naam' => 'Xbox'
        ]);
        $this->assertEquals("", $page);
    }

    /** @test */
    public function it_shows_an_error_when_user_has_no_permission() {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../admin/consoleToevoeg.php',
            [],
            [
                'naam' => 'Xbox'
            ]
        );

        $this->assertDatabaseMissing('consoles', [
            'naam' => 'Xbox'
        ]);
        $this->assertEquals("Geen permissie...", $page);
    }
}