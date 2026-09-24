<?php
namespace Tests\Admin;

use Tests\Factories\LinkFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class EditLinkTest extends TestCase
{
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
    public function it_shows_the_link_when_editting() {
        $this->login(null, Permissions::MANAGE_LINKS);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/linkBewerk.php',
            ['id' => $this->linkId]
        );

        $this->assertContains('Microsoft', $page);
        $this->assertContains('https://microsoft.com', $page);
    }

    /** @test */
    public function it_edits_a_link() {
        $this->login(null, Permissions::MANAGE_LINKS);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/linkBewerk.php',
            ['id' => $this->linkId],
            ['link' => 'Google', 'url' => 'https://google.com']
        );

        $this->assertDatabaseHas('links', [
            'linkid' => $this->linkId,
            'link' => 'Google',
            'url' => 'https://google.com',
        ]);
    }

    /** @test */
    public function it_gives_an_error_when_id_is_missing() {
        $this->login(null, Permissions::MANAGE_LINKS);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/linkBewerk.php'
        );

        $this->assertEquals('Geen id...', $page);
    }

    /** @test */
    public function it_redirects_when_user_is_unauthenticated() {
        $page = $this->visitPage(
            __DIR__ . '/../../admin/linkBewerk.php',
            ['id' => $this->linkId],
            ['link' => 'Google', 'url' => 'https://google.com']
        );

        $this->assertDatabaseHas('links', [
            'linkid' => $this->linkId,
            'link' => 'Microsoft',
            'url' => 'https://microsoft.com',
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_gives_an_error_when_user_is_unauthorized() {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../admin/linkBewerk.php',
            ['id' => $this->linkId],
            ['link' => 'Google', 'url' => 'https://google.com']
        );

        $this->assertDatabaseHas('links', [
            'linkid' => $this->linkId,
            'link' => 'Microsoft',
            'url' => 'https://microsoft.com',
        ]);
        $this->assertEquals("Geen permissie...", $page);
    }
}