<?php
namespace Tests\Admin;

use Tests\Factories\LinkFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class LinksTest extends TestCase {
    public static function getTables() {
        return [
            'users',
            'links',
        ];
    }

    /** @test */
    public function it_shows_all_links() {
        $this->login(null, Permissions::MANAGE_LINKS);

        foreach(range(1, 10) as $i) {
            LinkFactory::create(
                self::$pdo,
                'Test '.$i,
                'https://test'.$i.'.nl'
            );
        }

        $page = $this->visitPage(
            __DIR__ . '/../../admin/links.php'
        );

        foreach(range(1, 10) as $i) {
            $this->assertContains('Test '.$i, $page);
        }
    }

    /** @test */
    public function it_shows_redirects_when_user_is_unauthenticated() {
        foreach(range(1, 10) as $i) {
            LinkFactory::create(
                self::$pdo,
                'Test '.$i,
                'https://test'.$i.'.nl'
            );
        }

        $page = $this->visitPage(
            __DIR__ . '/../../admin/links.php'
        );

        $this->assertEquals("", $page);
    }

    /** @test */
    public function it_gives_an_error_when_the_user_has_no_permission() {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../admin/links.php'
        );

        $this->assertEquals('Geen permissie...', $page);
    }
}