<?php

namespace Tests\Pages;

use Tests\TestCase;

class LogoutTest extends TestCase
{
    /** @test */
    public function it_can_logout_the_user()
    {
        $this->markTestIncomplete('Unable to test without support for sessions and cookies');
    }
}
