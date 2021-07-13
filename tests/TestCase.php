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
        'zoekwoorden'
    ];
    protected $session = [];

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
        self::$pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
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

    protected function visitPage($pagePath, array $get = [], array $post = [])
    {
        $_GET = $get;
        $_POST = $post;

        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        if(empty($_POST)) {
            $_SERVER['REQUEST_METHOD'] = 'GET';
        } else {
            $_SERVER['REQUEST_METHOD'] = 'POST';
        }

        session_abort();
        ob_start();
        include $pagePath;
        $page = ob_get_contents();
        ob_end_clean();
        if(isset($_SESSION)) {
            $this->session = $_SESSION;
        }
        session_abort();
        return $page;
    }

    public function login($userId = null, $permissions = 0)
    {
        if($userId == null) {
            $userId = UserFactory::create(
                self::$pdo,
                'Mark',
                'secret',
                'example@example.com',
                '127.0.0.1',
                $permissions
            );
        }

        $_COOKIE['USERDATA'] = serialize([
           'userid' => $userId,
           'ip' => '127.0.0.1'
        ]);

        return $userId;
    }

    public function logout()
    {
        unset($_COOKIE['USERDATA']);
    }

    public function assertSessionHas($key)
    {
        $this->assertArrayHasKey($key, $this->session);
    }

    public function assertSessionHasNot($key)
    {
        $this->assertArrayNotHasKey($key, $this->session);
    }

    public function getSession($key)
    {
        return $this->session[$key];
    }

    public function assertDatabaseHas($table, array $fields)
    {
        $data = $this->getFieldsFromTable($table, $fields);

        $this->assertContains(
            $fields,
            $data,
            'Database contains: ' . print_r($data, true)
        );
    }

    public function assertDatabaseMissing($table, array $fields)
    {
        $data = $this->getFieldsFromTable($table, $fields);

        $this->assertNotContains(
            $fields,
            $data,
            'Database contains: ' . print_r($data, true)
        );
    }

    protected function getFieldsFromTable($table, array $fields)
    {
        $sql = 'SELECT ' . implode(',', array_keys($fields)) . ' FROM ' . $table . ';';
        $result = self::$pdo->query($sql);

        return $result->fetchAll();
    }
}