<?php

namespace Tests\Pages;

use Tests\Factories\UserFactory;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    /** @test */
    public function it_shows_the_update_profile_page()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../profiel.php'
        );

        $this->assertContains('Profiel bewerken', $page);
    }

    /** @test */
    public function it_shows_404_when_user_isnt_logged_in()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../profiel.php'
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_shows_the_error()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../profiel.php',
            ['error' => 'There is something wrong']
        );

        $this->assertContains('Profiel bewerken', $page);
        $this->assertContains('There is something wrong', $page);
    }

    /** @test */
    public function it_changes_the_users_password()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../profiel.php',
            [],
            [
                'username' => 'Mark',
                'wachtwoord' => 'secret',
                'wachtwoord_nieuw1' => 'somethingelse',
                'wachtwoord_nieuw2' => 'somethingelse'
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('users', [
            'username' => 'Mark',
            'password' => hash('sha256', 'somethingelse'),
            'email' => 'example@example.com'
        ]);
    }

    /** @test */
    public function it_changes_the_users_email_address()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../profiel.php',
            [],
            [
                'username' => 'Mark',
                'wachtwoord' => 'secret',
                'email' => 'something@something.com'
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('users', [
            'username' => 'Mark',
            'password' => hash('sha256', 'secret'),
            'email' => 'something@something.com'
        ]);
    }

    /** @test */
    public function it_checks_users_login_credentials_before_profile_update()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../profiel.php',
            [],
            [
                'username' => 'Mark',
                'wachtwoord' => 'hello',
                'email' => 'something@something.com'
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('users', [
            'username' => 'Mark',
            'password' => hash('sha256', 'secret'),
            'email' => 'example@example.com'
        ]);
    }

    /** @test */
    public function it_checks_if_the_logged_in_user_is_actually_the_updated_user()
    {
        UserFactory::create(
            self::$pdo,
            'Yogi',
            'secret',
            'somebody@else.com',
            '127.0.0.1'
        );

        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../profiel.php',
            [],
            [
                'username' => 'Yogi',
                'wachtwoord' => 'secret',
                'email' => 'something@something.com'
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('users', [
            'username' => 'Mark',
            'password' => hash('sha256', 'secret'),
            'email' => 'example@example.com'
        ]);
        $this->assertDatabaseHas('users', [
            'username' => 'Yogi',
            'password' => hash('sha256', 'secret'),
            'email' => 'somebody@else.com'
        ]);
    }

    /** @test */
    public function it_checks_if_all_post_fields_are_there_to_check_profile_credentials()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../profiel.php',
            [],
            [
                'username' => 'Mark',
                'email' => 'something@something.com'
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('users', [
            'username' => 'Mark',
            'password' => hash('sha256', 'secret'),
            'email' => 'example@example.com'
        ]);
    }

    /** @test */
    public function it_checks_if_the_new_passwords_are_equal()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../profiel.php',
            [],
            [
                'username' => 'Mark',
                'wachtwoord' => 'secret',
                'wachtwoord_nieuw1' => 'hello',
                'wachtwoord_nieuw2' => 'bye'
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('users', [
            'username' => 'Mark',
            'password' => hash('sha256', 'secret'),
            'email' => 'example@example.com'
        ]);
    }

    /** @test */
    public function it_checks_if_the_email_address_already_exists()
    {
        UserFactory::create(
            self::$pdo,
            'Yogi',
            'secret',
            'yogi@bear.com',
            '127.0.0.1'
        );

        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../profiel.php',
            [],
            [
                'username' => 'Mark',
                'wachtwoord' => 'secret',
                'email' => 'yogi@bear.com'
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('users', [
            'username' => 'Mark',
            'password' => hash('sha256', 'secret'),
            'email' => 'example@example.com'
        ]);
    }

    /** @test */
    public function it_should_not_update_profile_when_fields_are_empty()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../profiel.php',
            [],
            [
                'username' => 'Mark',
                'wachtwoord' => 'secret',
                'wachtwoord_nieuw1' => '',
                'wachtwoord_nieuw2' => '',
                'email' => ''
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('users', [
            'username' => 'Mark',
            'password' => hash('sha256', 'secret'),
            'email' => 'example@example.com'
        ]);
    }

    /** @test */
    public function it_expects_two_passwords_when_changing_passwords()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../profiel.php',
            [],
            [
                'username' => 'Mark',
                'wachtwoord' => 'secret',
                'wachtwoord_nieuw1' => 'hello'
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('users', [
            'username' => 'Mark',
            'password' => hash('sha256', 'secret'),
            'email' => 'example@example.com'
        ]);
    }

    /** @test */
    public function it_can_update_password_and_email_at_the_same_time()
    {
        $this->login();

        $page = $this->visitPage(
            __DIR__ . '/../../profiel.php',
            [],
            [
                'username' => 'Mark',
                'wachtwoord' => 'secret',
                'email' => 'something@something.com',
                'wachtwoord_nieuw1' => 'newpassword',
                'wachtwoord_nieuw2' => 'newpassword'
            ]
        );

        $this->assertEquals('', $page);
        $this->assertDatabaseHas('users', [
            'username' => 'Mark',
            'password' => hash('sha256', 'newpassword'),
            'email' => 'something@something.com'
        ]);
    }
}
