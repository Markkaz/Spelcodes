<?php

namespace Tests\Pages;

use Tests\Factories\LinkFactory;
use Tests\TestCase;

class LinksTestTest extends TestCase
{
    public static function getTables()
    {
        return [
            'links',
        ];
    }

    /** @test */
    public function it_shows_links()
    {
        LinkFactory::create(self::$pdo, 'Spelcodes', 'https://spelcodes.nl');
        LinkFactory::create(self::$pdo, 'Webdevils', 'https://webdevils.nl');

        $page = $this->visitPage(
            __DIR__ . '/../../links.php'
        );

        $this->assertContains('Spelcodes', $page);
        $this->assertContains('Webdevils', $page);
    }

    /** @test */
    public function it_counts_outgoing_clicks()
    {
        $linkId = LinkFactory::create(self::$pdo, 'Spelcodes', 'https://spelcodes.nl');

        $page = $this->visitPage(
            __DIR__ . '/../../uit.php',
            ['id' => $linkId]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('links', [
            'link' => 'Spelcodes',
            'outcomming' => 1
        ]);
    }

    /** @test */
    public function it_shows_404_when_id_isnt_provided()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../uit.php'
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_404_when_link_doesnt_exist()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../uit.php',
            ['id' => 999]
        );

        $this->assertEquals('', $page);
    }
}
