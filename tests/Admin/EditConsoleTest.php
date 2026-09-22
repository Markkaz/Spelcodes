<?php
namespace Tests\Admin;

use Tests\Factories\ConsoleFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class EditConsoleTest extends TestCase {
    protected $consoleId;

    public static function getTables()
    {
        return [
            'users',
            'consoles',
        ];
    }

    public function setUp() {
        parent::setUp();

        $this->consoleId = ConsoleFactory::create(
            self::$pdo,
            'Xbox'
        );
    }

    /** @test */
    public function it_shows_the_edit_form() {
        $this->login(null, Permissions::MANAGE_CONSOLES);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/consoleBewerk.php',
            ['id' => $this->consoleId]
        );

        $this->assertContains("Xbox", $page);
        $this->assertContains("Console bewerken", $page);
    }

    /** @test */
    public function it_edits_the_console() {
        $this->login(null, Permissions::MANAGE_CONSOLES);

        $this->visitPage(
            __DIR__ . '/../../admin/consoleBewerk.php',
            ['id' => $this->consoleId],
            ['naam' => 'Playstation']
        );

        $this->assertDatabaseHas('consoles', ['naam' => 'Playstation']);
    }

    /** @test */
    public function it_shows_an_error_when_id_is_missing() {
        $this->login(null, Permissions::MANAGE_CONSOLES);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/consoleBewerk.php'
        );

        $this->assertEquals("Geen id...", $page);
    }

    /** @test */
    public function it_redirects_when_user_is_not_authenticated() {
        $page = $this->visitPage(
            __DIR__ . '/../../admin/consoleBewerk.php',
            ['id' => $this->consoleId],
            ['naam' => 'Playstation']
        );

        $this->assertDatabaseMissing('consoles', ['naam' => 'Playstation']);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_an_error_when_no_permission() {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../admin/consoleBewerk.php',
            ['id' => $this->consoleId],
            ['naam' => 'Playstation']
        );

        $this->assertDatabaseMissing('consoles', ['naam' => 'Playstation']);
        $this->assertEquals("Geen permissie...", $page);
    }
}