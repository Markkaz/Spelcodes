<?php

namespace Tests\Pages;

use Tests\TestCase;

class LogoutTest extends TestCase
{
    public static function getTables()
    {
        return [];
    }

    /** @test */
    public function it_can_logout_the_user()
    {
        $this->markTestIncomplete('Unable to test without support for sessions and cookies');
    }
}
