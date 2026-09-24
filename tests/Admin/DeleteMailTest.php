<?php
namespace Tests\Admin;

use Tests\Factories\MailFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class DeleteMailTest extends TestCase
{
    protected $mailId;

    public static function getTables()
    {
        return [
            'users',
            'mail',
        ];
    }

    protected function setUp() {
        parent::setUp();

        $this->mailId = MailFactory::create(
            self::$pdo,
            'Yellow Submarine',
            'We all live in a Yellow Submarine',
            'test@test.nl'
        );
    }

    /** @test */
    public function it_asks_for_confirmation_before_deleting_an_email() {
        $this->login(null, Permissions::MANAGE_MAIL);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/mailVerwijder.php',
            ['mailid' => $this->mailId]
        );

        $this->assertContains('Mail verwijderen', $page);
        $this->assertContains(sprintf('<b>%s</b>', $this->mailId), $page);
    }

    /** @test */
    public function it_deletes_email_after_confirmation() {
        $this->login(null, Permissions::MANAGE_MAIL);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/mailVerwijder.php',
            ['mailid' => $this->mailId],
            ['delete' => true]
        );

        $this->assertDatabaseMissing('mail', [
            'mailid' => $this->mailId,
            'titel' => 'Yellow Submarine',
        ]);
    }

    /** @test */
    public function it_gives_an_error_when_mailid_is_missing() {
        $this->login(null, Permissions::MANAGE_MAIL);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/mailVerwijder.php',
            [],
            ['delete' => true]
        );

        $this->assertDatabaseHas('mail', [
            'mailid' => $this->mailId,
            'titel' => 'Yellow Submarine',
        ]);
        $this->assertEquals('Geen mailid...', $page);
    }

    /** @test */
    public function it_redirects_when_user_is_unauthenticated() {
        $page = $this->visitPage(
            __DIR__ . '/../../admin/mailVerwijder.php',
            ['mailid' => $this->mailId],
            ['delete' => true]
        );

        $this->assertDatabaseHas('mail', [
            'mailid' => $this->mailId,
            'titel' => 'Yellow Submarine',
        ]);
        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_an_error_when_user_unauthorised() {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../admin/mailVerwijder.php',
            ['mailid' => $this->mailId],
            ['delete' => true]
        );

        $this->assertDatabaseHas('mail', [
            'mailid' => $this->mailId,
            'titel' => 'Yellow Submarine',
        ]);
        $this->assertEquals('Geen permissie...', $page);
    }
}