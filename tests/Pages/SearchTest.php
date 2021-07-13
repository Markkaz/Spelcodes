<?php

namespace Tests\Pages;

use Tests\Factories\ConsoleFactory;
use Tests\Factories\GameFactory;
use Tests\TestCase;

class SearchTest extends TestCase
{
    public static function getTables()
    {
        return [
            'consoles',
            'spellen',
            'zoekwoorden',
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $consoleId = ConsoleFactory::create(self::$pdo, 'Xbox');
        GameFactory::create(
            self::$pdo,
            $consoleId,
            'Halo',
            'halo',
            'Bungie',
            'Microsoft',
            'https://bungie.com',
            'https://microsoft.com'
        );
        GameFactory::create(
            self::$pdo,
            $consoleId,
            'Sonic the Hedgehog',
            'sonic',
            'Sega',
            'Sega',
            'https://sega.com',
            'https://sega.com'
        );
    }


    /** @test */
    public function it_shows_search_results_when_it_finds_something()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../zoeken.php',
            ['zoek' => 'Halo']
        );

        $this->assertContains('Xbox', $page);
        $this->assertContains('Halo', $page);
        $this->assertNotContains('Sonic', $page);

        $this->assertDatabaseMissing('zoekwoorden', ['zoekwoord' => 'Halo']);
    }

    /** @test */
    public function it_searches_case_insensitive()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../zoeken.php',
            ['zoek' => 'hedgehog']
        );

        $this->assertContains('Xbox', $page);
        $this->assertContains('Sonic', $page);
        $this->assertNotContains('Halo', $page);

        $this->assertDatabaseMissing('zoekwoorden', ['zoekwoord' => 'hedgehog']);
    }

    /** @test */
    public function it_shows_no_results_when_it_finds_nothing()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../zoeken.php',
            ['zoek' => 'asdf;']
        );

        $this->assertContains('Helaas', $page);
        $this->assertDatabaseHas('zoekwoorden', ['zoekwoord' => 'asdf;']);
    }

    /** @test */
    public function it_shows_404_when_no_search_query_is_provided()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../zoeken.php'
        );

        $this->assertEquals('', $page);
    }
}
