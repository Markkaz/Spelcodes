<?php
namespace Tests\Admin;

use Tests\Factories\MailFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class MailTest extends TestCase {
    public static function getTables()
    {
        return [
            'users',
            'mail'
        ];
    }

    /** @test */
    public function it_shows_all_emails() {
        $this->login(null, Permissions::MANAGE_MAIL);

        $readId = MailFactory::create(self::$pdo, "Hello", "I want to say hello", "test@test.nl");
        MailFactory::read(self::$pdo, $readId);
        $unreadId = MailFactory::create(self::$pdo, "Yellow Submarine", "We all live in a yellow submarine", "test2@test.nl");

        $page = $this->visitPage(
            __DIR__ . '/../../admin/mail.php'
        );

        $this->assertContainsInOrder([
            sprintf("%s</td>", $readId),
            "Hello",
        ], $page);
        $this->assertContainsInOrder([
            sprintf("<b>%s</b>", $unreadId),
            "Yellow Submarine",
        ], $page);
    }

    /** @test */
    public function it_redirects_when_user_is_unauthenticated() {
        $page = $this->visitPage(
            __DIR__ . '/../../admin/mail.php'
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_error_when_user_unauthorised() {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../admin/mail.php'
        );

        $this->assertEquals('Geen permissie...', $page);
    }
}