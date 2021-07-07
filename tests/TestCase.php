<?php


namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Tests\Factories\UserFactory;

class TestCase extends BaseTestCase
{
    protected static $pdo;
    protected static $tables = [
        'users',
        'nieuws',
        'nieuwsreacties',
        'consoles',
        'spellen',
        'spellenview',
        'topics',
        'spellenhulp',
        'berichten',
        'stemmen',
        'links',
        'mail',
        'forum_categories',
        'forum_forums',
        'forum_topics',
        'forum_posts',
    ];

    public static function setUpBeforeClass()
    {
        self::createDatabaseConnection();
        self::createTables();
    }

    public static function tearDownAfterClass()
    {
        self::dropTables();
    }

    protected function setUp()
    {
        $this->emptyTables();
    }

    protected static function createDatabaseConnection()
    {
        self::$pdo = new \PDO('mysql:host=localhost;dbname=spelcodes', 'homestead', 'secret');
        self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    protected static function createTables()
    {
        foreach (self::$tables as $table) {
            self::$pdo->query(
                file_get_contents(__DIR__ . '/../database/' . $table . '.sql')
            );
        }
    }

    protected static function dropTables()
    {
        $sql = 'DROP table ' . implode(', ', self::$tables) . ';';
        self::$pdo->query($sql);
    }

    protected function emptyTables()
    {
        self::$pdo->query('SET FOREIGN_KEY_CHECKS=0');

        foreach (self::$tables as $table) {
            self::$pdo->query('TRUNCATE TABLE ' . $table);
        }

        self::$pdo->query('SET FOREIGN_KEY_CHECKS=1');
    }

    protected function visitPage($pagePath, array $get = [])
    {
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_GET = $get;

        session_abort();
        ob_start();
        include $pagePath;
        $page = ob_get_contents();
        ob_end_clean();
        session_abort();
        return $page;
    }

    public function login($permissions = 0)
    {
        $userId = UserFactory::create(
            self::$pdo,
            'Mark',
            'secret',
            'example@example.com',
            '127.0.0.1',
            $permissions
        );

        $_COOKIE['USERDATA'] = serialize([
           'userid' => $userId,
           'ip' => '127.0.0.1'
        ]);

        return $userId;
    }
}