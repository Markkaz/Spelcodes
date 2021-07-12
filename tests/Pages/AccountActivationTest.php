<?php

namespace Tests\Pages;

use Tests\Factories\UserFactory;
use Tests\TestCase;

class AccountActivationTest extends TestCase
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
    }

    /** @test */
    public function it_can_activate_an_unactivated_user()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../reg.php',
            ['id' => base64_encode($this->userId)]
        );

        $this->assertContains('Account geactiveerd', $page);
        $this->assertDatabaseHas('users', [
            'userid' => $this->userId,
            'activate' => true
        ]);
    }

    /** @test */
    public function it_cannot_activate_a_non_existent_user()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../reg.php',
            ['id' => base64_encode(999)]
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_cannot_activate_without_key()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../reg.php'
        );

        $this->assertEquals('', $page);
    }

    /** @test */
    public function it_cannot_activate_an_already_activated_user()
    {
        UserFactory::activate(self::$pdo, $this->userId);

        $page = $this->visitPage(
            __DIR__ . '/../../reg.php',
            ['id' => base64_encode($this->userId)]
        );

        $this->assertContains('Activering mislukt', $page);
    }
}
