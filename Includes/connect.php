<?php

define('MYSQL_USER', 'homestead');

define('MYSQL_PASS', 'secret');

define('MYSQL_HOST', 'localhost');

define('MYSQL_DB', 'spelcodes');



function connectDB()

{

  if(!$cDBH = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS))

  {

    return false;

  }

  if(!mysql_select_db(MYSQL_DB, $cDBH))

  {

    return false;

  }

  return $cDBH;

}

?>