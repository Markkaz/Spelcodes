<?php
namespace Tests\Admin;

use Tests\TestCase;
use Webdevils\Spelcodes\Permissions;

class IndexTest extends TestCase {

    public static function getTables()
    {
        return ['users'];
    }

    /** @test */
    public function it_shows_users_beheren_link_when_you_have_access() {
        $this->login(null, Permissions::MANAGE_USERS);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/index.php'
        );

        $this->assertContains("linkadmin1.gif", $page);
        $this->assertContains("Gebruikers beheren", $page);
    }

    /** @test */
    public function it_shows_consoles_beheren_link_when_you_have_access() {
        $this->login(null, Permissions::MANAGE_CONSOLES);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/index.php'
        );

        $this->assertContains("linkadmin3.gif", $page);
        $this->assertContains("Consoles beheren", $page);
    }

    /** @test */
    public function it_shows_spellen_beheren_link_when_you_have_spellen_beheren_permission() {
        $this->login(null, Permissions::MANAGE_SPELLEN);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/index.php'
        );

        $this->assertContains("linkadmin2.gif", $page);
        $this->assertContains("Spellen beheren", $page);
    }

    /** @test */
    public function it_shows_spellen_beheren_link_when_you_have_topics_beheren_permission() {
        $this->login(null, Permissions::MANAGE_SPELLEN_TOPICS);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/index.php'
        );

        $this->assertContains("linkadmin2.gif", $page);
        $this->assertContains("Spellen beheren", $page);
    }

    /** @test */
    public function it_shows_nieuws_beheren_link_when_you_have_permission() {
        $this->login(null, Permissions::MANAGE_NIEUWS);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/index.php'
        );

        $this->assertContains("linkadmin5.gif", $page);
        $this->assertContains("Nieuws beheren", $page);
    }

    /** @test */
    public function it_shows_links_beheren_when_you_have_permission() {
        $this->login(null, Permissions::MANAGE_LINKS);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/index.php'
        );

        $this->assertContains("linkadmin6.gif", $page);
        $this->assertContains("Links beheren", $page);
    }

    /** @test */
    public function it_shows_backup_maken_link_when_you_have_permission() {
        $this->login(null, Permissions::MANAGE_BACKUPS);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/index.php'
        );

        $this->assertContains("linkadmin4.gif", $page);
        $this->assertContains("Backup maken", $page);
    }

    /** @test */
    public function it_shows_mail_beheren_link_when_you_have_permission() {
        $this->login(null, Permissions::MANAGE_MAIL);

        $page = $this->visitPage(
            __DIR__ . '/../../admin/index.php'
        );

        $this->assertContains("linkadmin8.gif", $page);
        $this->assertContains("Mail beheren", $page);
    }

    /** @test */
    public function it_shows_no_links_when_no_permission() {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../admin/index.php'
        );

        $this->assertNotContains("linkadmin1.gif", $page);
        $this->assertNotContains("linkadmin3.gif", $page);
        $this->assertNotContains("linkadmin2.gif", $page);
        $this->assertNotContains("linkadmin5.gif", $page);
        $this->assertNotContains("linkadmin6.gif", $page);
        $this->assertNotContains("linkadmin4.gif", $page);
        $this->assertNotContains("linkadmin8.gif", $page);

        $this->assertNotContains("Gebruikers beheren", $page);
        $this->assertNotContains("Consoles beheren", $page);
        $this->assertNotContains("Spellen beheren", $page);
        $this->assertNotContains("Nieuws beheren", $page);
        $this->assertNotContains("Links beheren", $page);
        $this->assertNotContains("Backup maken", $page);
        $this->assertNotContains("Emails beheren", $page);
    }

    /** @test */
    public function it_should_redirect_when_not_authenticated() {
        $page = $this->visitPage(
            __DIR__ . '/../../admin/index.php'
        );

        $this->assertEquals("", $page);
    }
}