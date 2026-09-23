<?php
namespace Tests\Admin;

use Tests\Factories\ConsoleFactory;
use Tests\Factories\GameFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class DeleteFavoriteTest extends TestCase {
    protected $consoleId;
    protected $gameId;

    public static function getTables()
    {
        return [
            'users',
            'consoles',
            'spellen',
            'spellenview',
        ];
    }

    public function setUp() {
        parent::setUp();

        $this->consoleId = ConsoleFactory::create(
            self::$pdo,
            'Xbox'
        );

        $this->gameId = GameFactory::create(
            self::$pdo,
            $this->consoleId,
            'Halo',
            'halo',
            'Bungie',
            'Microsoft',
            'https://test.nl',
            'https://microsoft.com'
        );
        GameFactory::highlight(self::$pdo, $this->consoleId, $this->gameId);
    }

    /** @test */
    public function it_asks_for_confirmation_to_remove_a_favorite() {
        $this->login(null, Permissions::MANAGE_FAVORIETEN);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/addGameVerwijder.php',
            ['id' => $this->consoleId, 'spelid' => $this->gameId]
        );

        $this->assertContains(sprintf("spelID <b>%s</b>", $this->gameId), $page);
    }

    /** @test */
    public function it_removes_a_favorite() {
        $this->login(null, Permissions::MANAGE_FAVORIETEN);

        $this->visitPage(
            __DIR__ . '/../../admin/addGameVerwijder.php',
            ['id' => $this->consoleId, 'spelid' => $this->gameId],
            ['delete' => true]
        );

        $this->assertDatabaseMissing('spellenview', ['spelid' => $this->gameId, 'consoleid' => $this->consoleId]);
    }

    /** @test */
    public function it_gives_error_when_id_is_missing() {
        $this->login(null, Permissions::MANAGE_FAVORIETEN);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/addGameVerwijder.php',
            ['spelid' => $this->gameId],
            ['delete' => true]
        );

        $this->assertDatabaseHas('spellenview', ['spelid' => $this->gameId, 'consoleid' => $this->consoleId]);
        $this->assertEquals('Geen id en/of spelid...', $page);
    }

    /** @test */
    public function it_gives_error_when_spelid_is_missing() {
        $this->login(null, Permissions::MANAGE_FAVORIETEN);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/addGameVerwijder.php',
            ['id' => $this->consoleId],
            ['delete' => true]
        );

        $this->assertDatabaseHas('spellenview', ['spelid' => $this->gameId, 'consoleid' => $this->consoleId]);
        $this->assertEquals('Geen id en/of spelid...', $page);
    }

    /** @test */
    public function it_redirects_when_user_is_unauthenticated() {
        $page = $this->visitPage(
            __DIR__ . '/../../admin/addGameVerwijder.php',
            ['id' => $this->consoleId, 'spelid' => $this->gameId],
            ['delete' => true]
        );

        $this->assertDatabaseHas('spellenview', ['spelid' => $this->gameId, 'consoleid' => $this->consoleId]);
        $this->assertEquals("", $page);
    }

    /** @test */
    public function it_shows_an_error_when_user_has_no_permissions() {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../admin/addGameVerwijder.php',
            ['id' => $this->consoleId, 'spelid' => $this->gameId],
            ['delete' => true]
        );

        $this->assertDatabaseHas('spellenview', ['spelid' => $this->gameId, 'consoleid' => $this->consoleId]);
        $this->assertEquals("Geen permissie...", $page);
    }
}