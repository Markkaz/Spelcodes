<?php

namespace Tests\Pages;

use Tests\Factories\LinkFactory;
use Tests\TestCase;

class LinksTestTest extends TestCase
{
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
}
