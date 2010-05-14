<?php
error_reporting(E_ALL);

session_start();

/* Classes importeren */
include('Classes/User.php');

$cUser = new User();
$cUser -> logout();

header('Location: index.php');
?>