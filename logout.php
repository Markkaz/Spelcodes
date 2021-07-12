<?php
error_reporting(E_ALL & ~E_DEPRECATED);

session_start();

/* Classes importeren */
include_once('Classes/User.php');

$cUser = new User();
$cUser->logout();

header('Location: index.php');
