<?php
namespace Tests\Admin;

use Tests\Factories\UserFactory;
use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class UserPermissionTest extends TestCase {
    public static function getTables()
    {
        return [
            'users',
        ];
    }

    /** @test */
    public function it_shows_the_users_current_permissions() {
        $this->login(null, Permissions::MANAGE_USERS);

        $userId = UserFactory::create(
            self::$pdo,
            'test',
            'test',
            'test@test.nl',
            '127.0.0.1'
        );

        $page = $this->visitPage(
            __DIR__ . '/../../admin/userPermis.php',
            ['id' => $userId]
        );

        $this->assertNotContains('checked', $page);
    }

    /** @test */
    public function it_shows_the_users_current_permissions_if_they_have_any() {
        $this->login(null, Permissions::MANAGE_USERS);

        $userId = UserFactory::create(
            self::$pdo,
            'test',
            'test',
            'test@test.nl',
            '127.0.0.1',
            Permissions::all()
        );

        $page = $this->visitPage(
            __DIR__ . '/../../admin/userPermis.php',
            ['id' => $userId]
        );

        $this->assertContainsInOrder([
            sprintf('name="spellen" value=%s checked', Permissions::MANAGE_SPELLEN),
            sprintf('name="mod" value=%s checked', Permissions::FORUM_MODERATOR),
            sprintf('name="admin" value=%s checked', Permissions::FORUM_ADMIN),
            sprintf('name="users" value=%s checked', Permissions::MANAGE_USERS),
            sprintf('name="consoles" value=%s checked', Permissions::MANAGE_CONSOLES),
            sprintf('name="nieuws" value=%s checked', Permissions::MANAGE_NIEUWS),
            sprintf('name="links" value=%s checked', Permissions::MANAGE_LINKS),
            sprintf('name="topics" value=%s checked', Permissions::MANAGE_SPELLEN_TOPICS),
            sprintf('name="favorieten" value=%s checked', Permissions::MANAGE_FAVORIETEN),
            sprintf('name="backup" value=%s checked', Permissions::MANAGE_BACKUPS),
            sprintf('name="mails" value=%s checked', Permissions::MANAGE_MAIL),
        ], $page);
    }

    /** @test */
    public function it_changes_the_users_current_permissions() {
        $this->login(null, Permissions::MANAGE_USERS);

        $userId = UserFactory::create(
            self::$pdo,
            'test',
            'test',
            'test@test.nl',
            '127.0.0.1',
            Permissions::MANAGE_NIEUWS
        );

        $this->visitPage(
            __DIR__ . '/../../admin/userPermis.php',
            ['id' => $userId],
            ['permissie' => 1]
        );

        $this->assertDatabaseHas('users', [
            'username' => 'test',
            'email' => 'test@test.nl',
            'permis' => 0
        ]);
    }

    /** @test */
    public function it_adds_selected_permissions() {
        $this->login(null, Permissions::MANAGE_USERS);

        $userId = UserFactory::create(
            self::$pdo,
            'test',
            'test',
            'test@test.nl',
            '127.0.0.1'
        );

        $this->visitPage(
            __DIR__ . '/../../admin/userPermis.php',
            ['id' => $userId],
            [
                'permissie' => 1,
                'spellen' => Permissions::MANAGE_SPELLEN,
                'mod' => Permissions::FORUM_MODERATOR,
                'admin' => Permissions::FORUM_ADMIN,
                'users' => Permissions::MANAGE_USERS,
                'consoles' => Permissions::MANAGE_CONSOLES,
                'nieuws' => Permissions::MANAGE_NIEUWS,
                'links' => Permissions::MANAGE_LINKS,
                'topics' => Permissions::MANAGE_SPELLEN_TOPICS,
                'favorieten' => Permissions::MANAGE_FAVORIETEN,
                'backup' => Permissions::MANAGE_BACKUPS,
                'mails' => Permissions::MANAGE_MAIL,
            ]
        );

        $this->assertDatabaseHas('users', [
            'username' => 'test',
            'permis' => Permissions::all(),
        ]);
    }

    /** @test */
    public function it_shows_an_error_when_userid_is_missing() {
        $this->login(null, Permissions::MANAGE_USERS);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/userPermis.php'
        );

        $this->assertEquals("Geen id...", $page);
    }

    /** @test */
    public function it_shows_an_error_when_user_doesnot_have_the_right_permissions() {
        $userId = $this->login(null, Permissions::MANAGE_SPELLEN);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/userPermis.php',
            ['id' => $userId],
            ['permissie' => 1]
        );

        $this->assertDatabaseHas('users', [
            'permis' => Permissions::MANAGE_SPELLEN
        ]);
        $this->assertEquals("Geen permissie...", $page);
    }
}