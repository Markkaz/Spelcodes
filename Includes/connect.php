<?php
define('MYSQL_HOST', getenv('DB_HOST'));
define('MYSQL_PORT', getenv('DB_PORT'));
define('MYSQL_USER', getenv('DB_USER'));
define('MYSQL_PASS', getenv('DB_PASS'));
define('MYSQL_DB', getenv('DB_NAME'));

function connectDB()
{
    $sHost = MYSQL_HOST === 'localhost' ? MYSQL_HOST : MYSQL_HOST.':'.MYSQL_PORT;

  if(!$cDBH = mysql_connect($sHost, MYSQL_USER, MYSQL_PASS))
  {
    return false;
  }

  if(!mysql_select_db(MYSQL_DB, $cDBH))
  {
    return false;
  }

  return $cDBH;
}