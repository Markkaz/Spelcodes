<?php

namespace Webdevils\Spelcodes;

class Permissions
{
    const MANAGE_SPELLEN = 1;
    const FORUM_MODERATOR = 2;
    const FORUM_ADMIN = 4;
    const MANAGE_USERS = 8;
    const MANAGE_CONSOLES = 16;
    const MANAGE_NIEUWS = 32;
    const MANAGE_LINKS = 64;
    const MANAGE_SPELLEN_TOPICS = 128;
    const MANAGE_FAVORIETEN = 256;
    const MANAGE_BACKUPS = 512;
    const MANAGE_MAIL = 1024;

    public static function all() {
        return self::MANAGE_SPELLEN |
            self::FORUM_MODERATOR |
            self::FORUM_ADMIN |
            self::MANAGE_USERS |
            self::MANAGE_CONSOLES |
            self::MANAGE_NIEUWS |
            self::MANAGE_LINKS |
            self::MANAGE_SPELLEN_TOPICS |
            self::MANAGE_FAVORIETEN |
            self::MANAGE_BACKUPS |
            self::MANAGE_MAIL;
    }
}