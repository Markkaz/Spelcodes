<?php

namespace Webdevils\Spelcodes {
    function mail($receiver, $subject, $content)
    {
        \PHPUnit_Framework_Assert::assertEquals('example@example.com', $receiver);
        \PHPUnit_Framework_Assert::assertEquals('Registratie bij Spelcodes', $subject);
        \PHPUnit_Framework_Assert::assertContains('Mark', $content);
    }
}

namespace Tests\Pages {

    use Tests\Factories\UserFactory;
    use Tests\TestCase;

    class RegisterTest extends TestCase
    {
        /** @test */
        public function it_can_register_a_new_user()
        {
            $page = $this->visitPage(
                __DIR__ . '/../../registreren.php',
                [],
                [
                    'username' => 'Mark',
                    'password1' => 'secret',
                    'password2' => 'secret',
                    'email' => 'example@example.com'
                ]
            );
            $this->assertContains('Succesvol geregistreerd', $page);

            $this->assertDatabaseHas(
                'users',
                [
                    'username' => 'Mark',
                    'email' => 'example@example.com',
                    'activate' => 0,
                    'permis' => 0,
                    'posts' => 0
                ]
            );
        }

        /** @test */
        public function it_shows_the_register_form()
        {
            $page = $this->visitPage(
                __DIR__ . '/../../registreren.php'
            );

            $this->assertContains(
                'Je moet een correct e-mail adres invullen, omdat anders de registratie mail niet aankomt.',
                $page
            );
        }

        /** @test */
        public function it_can_only_register_a_user_with_a_new_username()
        {
            $user = UserFactory::create(
                self::$pdo,
                'Mark',
                'secret',
                'example@example.com',
                '127.0.0.1'
            );

            $page = $this->visitPage(
                __DIR__ . '/../../registreren.php',
                [],
                [
                    'username' => 'Mark',
                    'password1' => 'secret',
                    'password2' => 'secret',
                    'email' => 'example@example.com'
                ]
            );
            $this->assertContains('De gebruikersnaam bestaat al', $page);
        }

        /** @test */
        public function it_can_only_register_when_two_passwords_are_the_same()
        {
            $page = $this->visitPage(
                __DIR__ . '/../../registreren.php',
                [],
                [
                    'username' => 'Mark',
                    'password1' => 'secret',
                    'password2' => 'different',
                    'email' => 'example@example.com'
                ]
            );
            $this->assertContains('Het tweede wachtwoord kwam niet overeen met het eerste', $page);
        }

        /** @test */
        public function it_can_only_register_when_all_form_fields_are_present()
        {
            $page = $this->visitPage(
                __DIR__ . '/../../registreren.php',
                [],
                [
                    'username' => 'Mark',
                ]
            );
            $this->assertContains('Registreren', $page);
        }

        /** @test */
        public function it_can_only_register_when_a_valid_email_address_is_present()
        {
            $page = $this->visitPage(
                __DIR__ . '/../../registreren.php',
                [],
                [
                    'username' => 'Mark',
                    'password1' => 'secret',
                    'password2' => 'secret',
                    'email' => 'random-gibberish'
                ]
            );
            $this->assertNotContains('Succesvol geregistreerd', $page);

            $this->assertDatabaseMissing(
                'users',
                [
                    'username' => 'Mark',
                    'email' => 'random-gibberish',
                    'activate' => 0,
                    'permis' => 0,
                    'posts' => 0
                ]
            );
        }
    }
}