<?php
namespace Tests\Admin;

use Tests\Factories\ConsoleFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class DeleteConsoleTest extends TestCase {
    protected $consoleId;

    public static function getTables() {
        return [
            'users',
            'consoles',
        ];
    }

    public function setUp()
    {
        parent::setUp();

        $this->consoleId = ConsoleFactory::create(
            self::$pdo,
            'Xbox'
        );
    }

    /** @test */
    public function it_shows_the_delete_form() {
        $this->login(null, Permissions::MANAGE_CONSOLES);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/consoleVerwijder.php',
            ['id' => $this->consoleId]
        );

        $this->assertDatabaseHas('consoles', ['consoleid' => $this->consoleId]);
        $this->assertContains("Console verwijderen", $page);
    }

    /** @test */
    public function it_deletes_the_console() {
        $this->login(null, Permissions::MANAGE_CONSOLES);

        $this->visitPage(
            __DIR__ . '/../../admin/consoleVerwijder.php',
            ['id' => $this->consoleId],
            ['delete' => true]
        );

        $this->assertDatabaseMissing('consoles', ['consoleid' => $this->consoleId]);
    }

    /** @test */
    public function it_gives_error_when_missing_id() {
        $this->login(null, Permissions::MANAGE_CONSOLES);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/consoleVerwijder.php'
        );

        $this->assertEquals("Geen id...", $page);
    }

    /** @test */
    public function it_redirects_when_user_is_unauthenticated() {
        $page = $this->visitPage(
            __DIR__ . '/../../admin/consoleVerwijder.php',
            ['id' => $this->consoleId],
            ['delete' => true]
        );

        $this->assertDatabaseHas('consoles', ['consoleid' => $this->consoleId]);
        $this->assertEquals("", $page);
    }

    /** @test */
    public function it_gives_error_when_not_authorised() {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../admin/consoleVerwijder.php',
            ['id' => $this->consoleId],
            ['delete' => true]
        );

        $this->assertDatabaseHas('consoles', ['consoleid' => $this->consoleId]);
        $this->assertEquals("Geen permissie...", $page);
    }
}