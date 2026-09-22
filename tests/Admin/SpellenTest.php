<?php
namespace Tests\Admin;

use Tests\Factories\ConsoleFactory;
use Tests\Factories\GameFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class SpellenTest extends TestCase {
    protected $consoleId;

    public static function getTables() {
        return [
            'users',
            'consoles',
            'spellen',
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
    public function it_shows_a_list_of_spellen() {
        $this->login(null, Permissions::MANAGE_SPELLEN);

        foreach(range(1, 10) as $i) {
            GameFactory::create(
                self::$pdo,
                $this->consoleId,
                'Halo ' . $i,
                'halo' . $i,
                'Bungie',
                'Microsoft',
                'https://test.nl',
                'https://microsoft.com'
            );
        }

        $page = $this->visitPage(
            __DIR__ . '/../../admin/spellen.php'
        );

        foreach(range(1, 10) as $i) {
            $this->assertContains("Halo " . $i, $page);
        }

        $this->assertNotContains("Volgende", $page);
        $this->assertNotContains("Vorige", $page);
    }

    /** @test */
    public function it_paginates_when_there_are_more_than_50_games() {
        $this->login(null, Permissions::MANAGE_SPELLEN);

        foreach(range(1, 50) as $i) {
            GameFactory::create(
                self::$pdo,
                $this->consoleId,
                'Halo ' . $i,
                'halo ' . $i,
                'Bungie',
                'Microsoft',
                'https://test.nl',
                'https://microsoft.com'
            );

            GameFactory::create(
                self::$pdo,
                $this->consoleId,
                'Metal Gear Solid ' . $i,
                'metal' . $i,
                'test',
                'https://test.nl',
                'Microsoft',
                'https://microsoft.com'
            );

            GameFactory::create(
                self::$pdo,
                $this->consoleId,
                'GTA ' . $i,
                'gta' . $i,
                'Rockstar Games',
                'Microsoft',
                'https://rockstar.com',
                'https://microsoft.com'
            );
        }

        $page = $this->visitPage(
            __DIR__ . '/../../admin/spellen.php',
            ['p' => 1]
        );

        $this->assertContains("Halo", $page);
        $this->assertNotContains("GTA", $page);
        $this->assertNotContains("Metal Gear Solid", $page);

        $this->assertContains("Vorige", $page);
        $this->assertContains("Volgende", $page);
    }

    /** @test */
    public function it_redirects_when_not_logged_in() {
        $page = $this->visitPage(
            __DIR__ . '/../../admin/spellen.php'
        );
        $this->assertEquals("", $page);
    }

    /** @test */
    public function it_shows_an_error_when_no_permissions() {
        $this->login(null, Permissions::MANAGE_NIEUWS);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/spellen.php'
        );

        $this->assertEquals("Geen permissie...", $page);
    }

    /** @test */
    public function it_allows_to_add_edit_and_remove_games_with_right_permissions() {
        $this->login(null, Permissions::MANAGE_SPELLEN);

        GameFactory::create(
            self::$pdo,
            $this->consoleId,
            'GTA',
            'gta',
            'Rockstar Games',
            'Microsoft',
            'https://rockstar.com',
            'https://microsoft.com'
        );

        $page = $this->visitPage(
            __DIR__ . '/../../admin/spellen.php'
        );

        $this->assertContains("spelBewerk.php", $page);
        $this->assertContains("spelVerwijder.php", $page);

        $this->assertNotContains("topicsSpellen.php", $page);
    }

    /** @test */
    public function it_allows_to_edit_topics_when_it_has_the_permission() {
        $this->login(null, Permissions::MANAGE_SPELLEN_TOPICS);

        GameFactory::create(
            self::$pdo,
            $this->consoleId,
            'GTA',
            'gta',
            'Rockstar Games',
            'Microsoft',
            'https://rockstar.com',
            'https://microsoft.com'
        );

        $page = $this->visitPage(
            __DIR__ . '/../../admin/spellen.php'
        );

        $this->assertContains("topicsSpellen.php", $page);

        $this->assertNotContains("spelBewerk.php", $page);
        $this->assertNotContains("spelVerwijder.php", $page);
    }
}