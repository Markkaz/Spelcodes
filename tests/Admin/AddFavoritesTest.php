<?php
namespace Tests\Admin;

use Tests\Factories\ConsoleFactory;
use Tests\Factories\GameFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class AddFavoritesTest extends TestCase
{
    protected $consoleId;

    public static function getTables()
    {
        return [
            'users',
            'consoles',
            'spellen',
            'spellenview',
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
    public function it_shows_all_games_for_this_console_to_favorite() {
        $this->login(null, Permissions::MANAGE_FAVORIETEN);

        $otherConsole = ConsoleFactory::create(
            self::$pdo,
            'Playstation'
        );
        GameFactory::create(
            self::$pdo,
            $otherConsole,
            'Metal Gear Solid',
            'metal',
            'Test',
            'Test2',
            'https://test.nl',
            'https://test2.nl'
        );

        GameFactory::create(
            self::$pdo,
            $this->consoleId,
            'Halo',
            'halo',
            'Bungie',
            'Microsoft',
            'https://test.nl',
            'https://microsoft.com'
        );

        $page = $this->visitPage(
            __DIR__ . '/../../admin/addGameToevoeg.php',
            ['id' => $this->consoleId]
        );

        $this->assertContains("Halo", $page);
        $this->assertNotContains("Metal Gear Solid", $page);
    }

    /** @test */
    public function it_adds_a_game_to_favorites() {
        $this->login(null, Permissions::MANAGE_FAVORIETEN);

        $gameId = GameFactory::create(
            self::$pdo,
            $this->consoleId,
            'Halo',
            'halo',
            'Bungie',
            'Microsoft',
            'https://test.nl',
            'https://microsoft.com'
        );

        $this->visitPage(
            __DIR__ . '/../../admin/addGameAdd.php',
            ['id' => $this->consoleId, 'spelid' => $gameId]
        );

        $this->assertDatabaseHas('spellenview', ['consoleid' => $this->consoleId, 'spelid' => $gameId]);
    }

    /** @test */
    public function it_gives_an_error_when_console_id_is_missing() {
        $this->login(null, Permissions::MANAGE_FAVORIETEN);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/addGameToevoeg.php'
        );

        $this->assertEquals('Geen id...', $page);
    }

    /** @test */
    public function it_gives_an_error_when_console_id_is_missing_while_adding_favorite() {
        $this->login(null, Permissions::MANAGE_FAVORIETEN);

        $spelId = GameFactory::create(
            self::$pdo,
            $this->consoleId,
            'Halo',
            'halo',
            'Bungie',
            'Microsoft',
            'https://test.nl',
            'https://microsoft.com'
        );

        $page = $this->visitPage(
            __DIR__ . '/../../admin/addGameAdd.php',
            ['spelid' => $spelId]
        );

        $this->assertEquals("Geen id en/of spelid...", $page);
    }

    /** @test */
    public function it_gives_an_error_when_spelid_is_missing_while_adding_favorite() {
        $this->login(null, Permissions::MANAGE_FAVORIETEN);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/addGameAdd.php',
            ['id' => $this->consoleId]
        );

        $this->assertEquals("Geen id en/of spelid...", $page);
    }

    /** @test */
    public function it_gives_an_error_when_favoriting_without_permission() {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../admin/addGameToevoeg.php',
            ['id' => $this->consoleId]
        );

        $this->assertEquals("Geen permissie...", $page);
    }

    /** @test */
    public function it_gives_an_error_when_adding_a_favorite_without_permission() {
        $this->login();

        $gameId = GameFactory::create(
            self::$pdo,
            $this->consoleId,
            'Halo',
            'halo',
            'Bungie',
            'Microsoft',
            'https://test.nl',
            'https://microsoft.com'
        );

        $page = $this->visitPage(
            __DIR__ . '/../../admin/addGameAdd.php',
            ['id' => $this->consoleId, 'spelid' => $gameId]
        );

        $this->assertEquals('Geen permissie...', $page);
    }
}