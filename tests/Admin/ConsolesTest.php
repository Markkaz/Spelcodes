<?php
namespace Tests\Admin;

use Tests\Factories\ConsoleFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class ConsolesTest extends TestCase {
    public static function getTables() {
        return [
            'users',
            'consoles',
        ];
    }

    public function setUp()
    {
        parent::setUp();

        ConsoleFactory::create(
            self::$pdo,
            'Xbox'
        );
        ConsoleFactory::create(
            self::$pdo,
            'Playstation'
        );
    }

    /** @test */
    public function it_shows_all_consoles() {
        $this->login(null, Permissions::MANAGE_CONSOLES);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/consoles.php'
        );

        $this->assertContains("Xbox", $page);
        $this->assertContains("Playstation", $page);
    }

    /** @test */
    public function it_shows_favorieten_when_user_has_permissions() {
        $this->login(null, Permissions::MANAGE_FAVORIETEN);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/consoles.php'
        );

        $this->assertContains("addGame.php", $page);
        $this->assertNotContains("consoleBewerk.php", $page);
        $this->assertNotContains("consoleVerwijder.php", $page);
        $this->assertNotContains("consoleToevoeg.php", $page);
    }

    /** @test */
    public function it_shows_games_edit_buttons_when_user_has_permissions() {
        $this->login(null, Permissions::MANAGE_CONSOLES);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/consoles.php'
        );

        $this->assertNotContains("addGame.php", $page);
        $this->assertContains("consoleBewerk.php", $page);
        $this->assertContains("consoleVerwijder.php", $page);
        $this->assertContains("consoleToevoeg.php", $page);
    }

    /** @test */
    public function it_redirects_when_user_is_not_authenticated() {
        $page = $this->visitPage(
            __DIR__ . '/../../admin/consoles.php'
        );

        $this->assertEquals("", $page);
    }

    /** @test */
    public function it_shows_error_when_user_does_not_have_permission() {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../admin/consoles.php'
        );

        $this->assertEquals("Geen permissie...", $page);
    }
}