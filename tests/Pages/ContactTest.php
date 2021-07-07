<?php

namespace Tests\Pages;

use Tests\TestCase;

class ContactTest extends TestCase
{
    /** @test */
    public function it_shows_contact_form()
    {
        $page = $this->visitPage(__DIR__ . '/../../contact.php');

        $this->assertContains(
            'Let op: ALs je een vraag hebt, zet er dan een email adres bij. Anders kunnen wij niet antwoorden:',
            $page
        );
    }

    /** @test */
    public function it_stores_a_valid_email()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../contact.php',
            [],
            [
                'titel' => 'Hello!',
                'email' => 'example@example.com',
                'bericht' => 'Hello, from me!'
            ]
        );

        $this->assertContains('Email verzonden', $page);
        $this->assertDatabaseHas('mail', [
            'titel' => 'Hello!',
            'email' => 'example@example.com',
            'bericht' => 'Hello, from me!',
            'gelezen' => 0
        ]);
    }

    /** @test */
    public function it_shows_contact_form_when_no_title_was_provided()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../contact.php',
            [],
            [
                'email' => 'example@example.com',
                'bericht' => 'Hello, from me!'
            ]
        );

        $this->assertContains(
            'Let op: ALs je een vraag hebt, zet er dan een email adres bij. Anders kunnen wij niet antwoorden:',
            $page
        );
        $this->assertDatabaseMissing('mail', [
            'titel' => '',
            'email' => 'example@example.com',
            'bericht' => 'Hello, from me!',
            'gelezen' => 0
        ]);
    }

    /** @test */
    public function it_shows_contact_form_when_no_email_was_provided()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../contact.php',
            [],
            [
                'titel' => 'Hello!',
                'bericht' => 'Hello, from me!'
            ]
        );

        $this->assertContains(
            'Let op: ALs je een vraag hebt, zet er dan een email adres bij. Anders kunnen wij niet antwoorden:',
            $page
        );
        $this->assertDatabaseMissing('mail', [
            'titel' => 'Hello!',
            'bericht' => 'Hello, from me!',
            'gelezen' => 0
        ]);
    }

    /** @test */
    public function it_shows_contact_form_when_no_body_was_provided()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../contact.php',
            [],
            [
                'titel' => 'Hello!',
                'email' => 'example@example.com'
            ]
        );

        $this->assertContains(
            'Let op: ALs je een vraag hebt, zet er dan een email adres bij. Anders kunnen wij niet antwoorden:',
            $page
        );
        $this->assertDatabaseMissing('mail', [
            'titel' => 'Hello!',
            'email' => 'example@example.com',
            'bericht' => '',
            'gelezen' => 0
        ]);
    }
}
