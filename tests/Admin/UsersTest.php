<?php
namespace Tests\Admin;

use Tests\Factories\UserFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class UsersTest extends TestCase {
    public static function getTables() {
        return [
            'users',
        ];
    }

    /** @test */
    public function it_shows_all_users() {
        $id = UserFactory::create(
            self::$pdo,
            "Markkaz",
            "Markkaz",
            "markkaz@test.nl",
            "127.0.0.1",
            Permissions::MANAGE_NIEUWS | Permissions::MANAGE_USERS
        );

        UserFactory::create(
            self::$pdo,
            "Kamikaze",
            "Kamikaze",
            "kamikaze@test.nl",
            "127.0.0.2"
        );

        $this->login($id);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/users.php'
        );

        $this->assertContains("Users beheren", $page);
        $this->assertContainsInOrder(["Markkaz", "Kamikaze"], $page);
    }

    /** @test */
    public function it_redirects_when_unauthenticated() {
        $page = $this->visitPage(
            __DIR__ . '/../../admin/users.php'
        );

        $this->assertEquals("", $page);
    }

    /** @test */
    public function it_gives_error_when_no_permission() {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../admin/users.php'
        );

        $this->assertEquals("Geen permissie...", $page);
    }
}