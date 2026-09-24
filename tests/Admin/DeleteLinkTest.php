<?php
namespace Tests\Admin;

use Tests\Factories\LinkFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class DeleteLinkTest extends TestCase {
    protected $linkId;

    public static function getTables()
    {
        return [
            'users',
            'links',
        ];
    }

    public function setUp()
    {
        parent::setUp();

        $this->linkId = LinkFactory::create(
            self::$pdo,
            'Microsoft',
            'https://microsoft.com'
        );
    }

    /** @test */
    public function it_asks_for_confirmation_before_deleting() {
        $this->login(null, Permissions::MANAGE_LINKS);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/linkVerwijder.php',
            ['id' => $this->linkId]
        );

        $this->assertContains(sprintf('<b>%s</b>', $this->linkId), $page);
    }

    /** @test */
    public function it_deletes_after_confirmation() {
        $this->login(null, Permissions::MANAGE_LINKS);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/linkVerwijder.php',
            ['id' => $this->linkId],
            ['delete' => true]
        );

        $this->assertDatabaseMissing('links', [
            'linkid' => $this->linkId,
            'link' => 'Microsoft',
            'url' => 'https://microsoft.com'
        ]);
    }

    /** @test */
    public function it_shows_an_error_when_id_is_missing() {
        $this->login(null, Permissions::MANAGE_LINKS);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/linkVerwijder.php',
            [],
            ['delete' => true]
        );

        $this->assertDatabaseHas('links', [
            'linkid' => $this->linkId,
            'link' => 'Microsoft',
            'url' => 'https://microsoft.com'
        ]);
        $this->assertEquals('Geen id...', $page);
    }

    /** @test */
    public function it_redirects_when_user_is_unauthenticated() {
        $page = $this->visitPage(
            __DIR__ . '/../../admin/linkVerwijder.php',
            ['id' => $this->linkId],
            ['delete' => true]
        );

        $this->assertDatabaseHas('links', [
            'linkid' => $this->linkId,
            'link' => 'Microsoft',
            'url' => 'https://microsoft.com'
        ]);
        $this->assertEquals("", $page);
    }

    /** @test */
    public function it_shows_error_when_user_is_unauthorized()
    {
        $this->login(null);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/linkVerwijder.php',
            ['id' => $this->linkId],
            ['delete' => true]
        );
        $this->assertEquals("Geen permissie...", $page);
    }
}