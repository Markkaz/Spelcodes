<?php

namespace Tests\Pages;

use Tests\Factories\UserFactory;
use Tests\TestCase;

function setcookie($key, $value, $expiration)
{
    var_dump($key);
    $_COOKIE[$key] = $value;
}

class LoginTest extends TestCase
{
    private $userId;

    protected function setUp()
    {
        parent::setUp();

        $this->userId = UserFactory::create(
            self::$pdo,
            'Mark',
            'secret',
            'example@example.com',
            '127.0.0.1'
        );
        UserFactory::activate(self::$pdo, $this->userId);
    }


    /** @test */
    public function it_shows_the_login_form()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../loginForm.php'
        );

        $this->assertContains('Gebruikersnaam:', $page);
        $this->assertContains('Wachtwoord:', $page);
    }

    /** @test */
    public function it_logs_in_the_user()
    {
        $this->visitPage(
            __DIR__ . '/../../login.php',
            [],
            [
                'username' => 'Mark',
                'password' => 'secret'
            ]
        );
        $this->assertSessionHas('USERDATA');

        $sessionData = unserialize($this->getSession('USERDATA'));
        $this->assertEquals($this->userId, $sessionData['userid']);
        $this->assertEquals(0, $sessionData['permis']);
    }

    /** @test */
    public function it_shows_the_login_form_with_errors_when_the_wrong_credentials_are_provided()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../login.php',
            [],
            [
                'username' => 'Mark',
                'password' => 'wrong',
            ]
        );

        $this->assertContains('Foutief wachtwoord/gebruikersnaam', $page);
    }

    /** @test */
    public function it_doesnt_login_an_inactive_user()
    {
        UserFactory::deactivate(self::$pdo, $this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../login.php',
            [],
            [
                'username' => 'Mark',
                'password' => 'secret',
            ]
        );

        $this->assertContains('Foutief wachtwoord/gebruikersnaam', $page);
    }
}
