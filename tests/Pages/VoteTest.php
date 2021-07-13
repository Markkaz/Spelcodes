<?php

namespace Tests\Pages;

use Tests\Factories\ConsoleFactory;
use Tests\Factories\GameFactory;
use Tests\TestCase;

class VoteTest extends TestCase
{
    private $consoleId;
    private $gameId;

    public static function getTables()
    {
        return [
            'consoles',
            'spellen',
            'stemmen',
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->consoleId = ConsoleFactory::create(self::$pdo, 'Xbox');

        $this->gameId = GameFactory::create(
            self::$pdo,
            $this->consoleId,
            'Halo',
            'halo',
            'Bungie',
            'Microsoft',
            'https://bungie.com',
            'https://microsoft.com'
        );
        GameFactory::vote(self::$pdo, $this->gameId, 4, '127.0.0.2');
    }


    /** @test */
    public function it_can_vote_on_a_game()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../stemmen.php',
            ['spelid' => $this->gameId],
            ['stem' => 3]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('stemmen', [
            'spelid' => $this->gameId,
            'ip' => '127.0.0.1'
        ]);
        $this->assertDatabaseHas('spellen', [
            'spelid' => $this->gameId,
            'stemmen' => 2,
            'rating' => 7
        ]);
    }

    /** @test */
    public function it_can_vote_on_another_game_with_same_ip()
    {
        GameFactory::vote(self::$pdo, $this->gameId, 5, '127.0.0.1');

        $gameId = GameFactory::create(
            self::$pdo,
            $this->consoleId,
            'Sonic the Hedgehog',
            'sonic',
            'Sega',
            'Sega',
            'https://sega.com',
            'https://sega.com'
        );

        $page = $this->visitPage(
            __DIR__ . '/../../stemmen.php',
            ['spelid' => $gameId],
            ['stem' => 3]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('spellen', [
            'spelid' => $gameId,
            'stemmen' => 1,
            'rating' => 3
        ]);
    }

    /** @test */
    public function it_cannot_vote_multiple_times_on_the_same_game_with_same_ip()
    {
        GameFactory::vote(self::$pdo, $this->gameId, 5, '127.0.0.1');

        $page = $this->visitPage(
            __DIR__ . '/../../stemmen.php',
            ['spelid' => $this->gameId],
            ['stem' => 3]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('spellen', [
            'spelid' => $this->gameId,
            'stemmen' => 2,
            'rating' => 9
        ]);
    }

    /** @test */
    public function it_shows_404_when_no_game_is_specified()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../stemmen.php',
            [],
            ['stem' => 3]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseMissing('stemmen', [
            'spelid' => $this->gameId,
            'ip' => '127.0.0.1'
        ]);
        $this->assertDatabaseHas('spellen', [
            'spelid' => $this->gameId,
            'stemmen' => 1,
            'rating' => 4
        ]);
    }

    /** @test */
    public function it_shows_404_when_game_doesnt_exist()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../stemmen.php',
            ['spelid' => 999],
            ['stem' => 3]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseMissing('stemmen', [
            'spelid' => 999,
            'ip' => '127.0.0.1'
        ]);
    }

    /** @test */
    public function it_shows_404_when_vote_is_invalid()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../stemmen.php',
            ['spelid' => $this->gameId],
            ['stem' => 25]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseMissing('stemmen', [
            'spelid' => $this->gameId,
            'ip' => '127.0.0.1'
        ]);
        $this->assertDatabaseHas('spellen', [
            'spelid' => $this->gameId,
            'stemmen' => 1,
            'rating' => 4
        ]);
    }
}
