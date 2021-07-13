<?php

namespace Tests\Pages;

use Tests\Factories\ConsoleFactory;
use Tests\Factories\GameFactory;
use Tests\TestCase;

class ConsoleTest extends TestCase
{
    public static function getTables()
    {
        return [
            'consoles',
            'spellen',
            'spellenview',
        ];
    }

    /** @test */
    public function it_shows_the_console_name_at_the_top()
    {
        $consoleId = ConsoleFactory::create(self::$pdo, 'Xbox');

        $page = $this->visitPage(
            __DIR__ . '/../../consoles.php',
            ['id' => $consoleId]
        );

        $this->assertContains('Xbox', $page);
    }

    /** @test */
    public function it_gives_404_if_console_doesnt_exist()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../consoles.php',
            ['id' => 999]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_gives_404_if_console_id_isnt_provided()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../consoles.php'
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_highlighted_games_for_the_console_when_no_letter_is_selected()
    {
        $currentConsoleId = ConsoleFactory::create(self::$pdo, 'Current console');
        $otherConsoleId = ConsoleFactory::create(self::$pdo, 'Other console');

        foreach (range(1, 3) as $i) {
            $gameId = GameFactory::create(
                self::$pdo,
                $currentConsoleId,
                'Visible game '.$i,
                'visible-game-'.$i,
                'Developer',
                'Publisher',
                'https://developer.com',
                'https://publisher.com'
            );
            GameFactory::highlight(self::$pdo, $currentConsoleId, $gameId);
        }

        $gameId = GameFactory::create(
            self::$pdo,
            $otherConsoleId,
            'Invisible game',
            'invisible-game',
            'Developer',
            'Publisher',
            'https://developer.com',
            'https://publisher.com'
        );
        GameFactory::highlight(self::$pdo, $otherConsoleId, $gameId);

        $page = $this->visitPage(
            __DIR__ . '/../../consoles.php',
            ['id' => $currentConsoleId]
        );

        $this->assertContains('Visible game 1', $page);
        $this->assertContains('Visible game 2', $page);
        $this->assertContains('Visible game 3', $page);

        $this->assertContains('visible-game-1', $page);
        $this->assertContains('visible-game-2', $page);
        $this->assertContains('visible-game-3', $page);

        $this->assertNotContains('Invisible game', $page);
    }

    /** @test */
    public function it_shows_last_10_games_when_no_letter_is_selected()
    {
        $consoleId = ConsoleFactory::create(self::$pdo, 'Xbox');

        GameFactory::create(
            self::$pdo,
            $consoleId,
            'Old game',
            'old-game',
            'Developer',
            'Publisher',
            'https://developer.com',
            'https://publisher.com'
        );

        foreach (range(1, 10) as $i) {
            GameFactory::create(
                self::$pdo,
                $consoleId,
                'New game '.$i,
                'new-game-'.$i,
                'Developer',
                'Publisher',
                'https://developer.com',
                'https://publisher.com'
            );
        }

        $page = $this->visitPage(
            __DIR__ . '/../../consoles.php',
            ['id' => $consoleId]
        );

        $this->assertContains('New game 1', $page);
        $this->assertContains('New game 10', $page);
        $this->assertNotContains('Old game', $page);
    }

    /** @test */
    public function it_shows_games_starting_with_a_letter()
    {
        $consoleId = ConsoleFactory::create(self::$pdo, 'Xbox');

        GameFactory::create(
            self::$pdo,
            $consoleId,
            'A - starts with A',
            'a-starts-with-a',
            'Developer',
            'Publisher',
            'https://developer.com',
            'https://publisher.com'
        );
        GameFactory::create(
            self::$pdo,
            $consoleId,
            'B - starts with B',
            'b-starts-with-b',
            'Developer',
            'Publisher',
            'https://developer.com',
            'https://publisher.com'
        );

        $page = $this->visitPage(
            __DIR__ . '/../../consoles.php',
            [
                'id' => $consoleId,
                'letter' => 'A'
            ]
        );

        $this->assertContains('A - starts with A', $page);
        $this->assertNotContains('B - starts with B', $page);
    }

    /** @test */
    public function it_shows_games_starting_with_a_number()
    {
        $consoleId = ConsoleFactory::create(self::$pdo, 'Xbox');

        GameFactory::create(
            self::$pdo,
            $consoleId,
            '7 - starts with 7',
            '7-starts-with-7',
            'Developer',
            'Publisher',
            'https://developer.com',
            'https://publisher.com'
        );
        GameFactory::create(
            self::$pdo,
            $consoleId,
            'B - starts with B',
            'b-starts-with-b',
            'Developer',
            'Publisher',
            'https://developer.com',
            'https://publisher.com'
        );

        $page = $this->visitPage(
            __DIR__ . '/../../consoles.php',
            [
                'id' => $consoleId,
                'letter' => '#'
            ]
        );

        $this->assertContains('7 - starts with 7', $page);
        $this->assertNotContains('B - starts with B', $page);
    }
}
