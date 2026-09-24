<?php
namespace Tests\Admin;

use Tests\Factories\MailFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class ShowMailTest extends TestCase {
    protected $mailId;

    protected function setUp() {
        parent::setUp();

        $this->mailId = MailFactory::create(
            self::$pdo,
            'Yellow Submarine',
            'We all live in a Yellow Submarine',
            'test@test.nl'
        );
    }

    public static function getTables() {
        return [
            'users',
            'mail',
        ];
    }

    /** @test */
    public function it_shows_mail_and_marks_it_read() {
        $this->login(null, Permissions::MANAGE_MAIL);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/showMail.php',
            ['mailid' => $this->mailId]
        );

        $this->assertContains('Yellow Submarine', $page);
        $this->assertContains('We all live in a Yellow Submarine', $page);
        $this->assertDatabaseHas('mail', [
            'mailid' => $this->mailId,
            'gelezen' => true
        ]);
    }

    /** @test */
    public function it_shows_error_when_emailid_is_missing() {
        $this->login(null, Permissions::MANAGE_MAIL);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/showMail.php'
        );

        $this->assertEquals("Geen mailid...", $page);
    }

    /** @test */
    public function it_redirects_unauthenticated_users() {
        $page = $this->visitPage(
            __DIR__ . '/../../admin/showMail.php',
            ['mailid' => $this->mailId]
        );

        $this->assertDatabaseHas('mail', [
            'mailid' => $this->mailId,
            'gelezen' => false
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_error_when_user_unauthorised()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../admin/showMail.php',
            ['mailid' => $this->mailId]
        );

        $this->assertDatabaseHas('mail', [
            'mailid' => $this->mailId,
            'gelezen' => false
        ]);
        $this->assertEquals('Geen permissie...', $page);
    }
}