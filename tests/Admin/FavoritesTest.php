<?php
namespace Tests\Admin;

use Tests\Factories\ConsoleFactory;
use Tests\Factories\GameFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class FavoritesTest extends TestCase {
    private $consoleId;

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

        foreach(range(1, 5) as $i) {
            $gameId = GameFactory::create(
                self::$pdo,
                $this->consoleId,
                'Halo ' . $i,
                'halo',
                'Bungie',
                'Microsoft',
                'https://test.nl',
                'https://microsoft.com'
            );
            GameFactory::highlight(self::$pdo, $this->consoleId, $gameId);
        }
    }

    /** @test */
    public function it_shows_the_highlighted_games_for_this_console() {
        $this->login(null, Permissions::MANAGE_FAVORIETEN);

        GameFactory::create(
            self::$pdo,
            $this->consoleId,
            'GTA',
            'gta',
            'Rockstar Games',
            'Microsoft',
            'https://test.nl',
            'https://microsoft.com'
        );

        $consoleId = ConsoleFactory::create(
            self::$pdo,
            'Playstation'
        );
        $gameId = GameFactory::create(
            self::$pdo,
            $consoleId,
            'Metal Gear Solid',
            'metal',
            'test',
            'test2',
            'https://test.nl',
            'https://test2.nl'
        );
        GameFactory::highlight(self::$pdo, $consoleId, $gameId);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/addGame.php',
            ['id' => $this->consoleId]
        );

        $this->assertContains("Halo", $page);
        $this->assertNotContains("GTA", $page);
        $this->assertNotContains("Metal Gear Solid", $page);
    }

    /** @test */
    public function it_shows_an_error_when_consoleId_is_missing() {
        $this->login(null, Permissions::MANAGE_FAVORIETEN);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/addGame.php'
        );

        $this->assertEquals("Geen id...", $page);
    }

    /** @test */
    public function it_redirects_when_user_is_unauthenticated() {
        $page = $this->visitPage(
            __DIR__ . '/../../admin/addGame.php',
            ['id' => $this->consoleId]
        );

        $this->assertEquals("", $page);
    }

    /** @test */
    public function it_shows_an_error_when_user_has_no_permission() {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../admin/addGame.php',
            ['id' => $this->consoleId]
        );

        $this->assertEquals("Geen permissie...", $page);
    }
}