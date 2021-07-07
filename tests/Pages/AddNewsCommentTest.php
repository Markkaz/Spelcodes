<?php

namespace Tests\Pages;

use Tests\Factories\NewsFactory;
use Tests\TestCase;

class AddNewsCommentTest extends TestCase
{
    private $userId;
    private $newsId;

    protected function setUp()
    {
        parent::setUp();

        $this->userId = $this->login();

        $this->newsId = NewsFactory::create(
            self::$pdo,
            $this->userId,
            'News item',
            'Body of news item'
        );
    }


    /** @test */
    public function it_adds_a_news_comment_for_a_logged_in_user()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../addNieuws.php',
            ['id' => $this->newsId],
            ['reactie' => 'A nice comment!']
        );

        $this->assertEquals('', $page);

        $this->assertDatabaseHas('nieuwsreacties', [
            'nieuwsid' => $this->newsId,
            'userid' => $this->userId,
            'bericht' => 'A nice comment!'
        ]);
    }

    /** @test */
    public function it_shows_404_when_newsid_parameter_is_missing()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../addNieuws.php',
            [],
            ['reactie' => 'A nice comment!']
        );

        $this->assertEquals('', $page);

        $this->assertDatabaseMissing('nieuwsreacties', [
            'nieuwsid' => $this->newsId,
            'userid' => $this->userId,
            'bericht' => 'A nice comment!'
        ]);
    }

    /** @test */
    public function it_shows_404_when_newsitem_doesnt_exist()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../addNieuws.php',
            ['id' => 999],
            ['reactie' => 'A nice comment!']
        );

        $this->assertEquals('', $page);

        $this->assertDatabaseMissing('nieuwsreacties', [
            'nieuwsid' => 999,
            'userid' => $this->userId,
            'bericht' => 'A nice comment!'
        ]);
    }

    /** @test */
    public function it_shows_404_when_form_wasnt_posted()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../addNieuws.php',
            ['id' => $this->newsId]
        );

        $this->assertEquals('', $page);

        $this->assertDatabaseMissing('nieuwsreacties', [
            'nieuwsid' => $this->newsId,
            'userid' => $this->userId,
            'bericht' => ''
        ]);
    }

    /** @test */
    public function it_redirects_to_login_form_when_user_isnt_logged_in()
    {
        $this->logout();

        $page = $this->visitPage(
            __DIR__ . '/../../addNieuws.php',
            ['id' => $this->newsId],
            ['reactie' => 'A nice comment!']
        );

        $this->assertEquals('', $page);

        $this->assertDatabaseMissing('nieuwsreacties', [
            'nieuwsid' => $this->newsId,
            'userid' => $this->userId,
            'bericht' => 'A nice comment!'
        ]);
    }

    /** @test */
    public function it_increases_the_comments_counter_for_the_user()
    {
        $page = $this->visitPage(
            __DIR__ . '/../../addNieuws.php',
            ['id' => $this->newsId],
            ['reactie' => 'A nice comment!']
        );

        $this->assertDatabaseHas('users', [
            'username' => 'Mark',
            'posts' => 1
        ]);
    }
}
