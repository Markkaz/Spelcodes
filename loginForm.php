<?php
error_reporting(E_ALL);

session_start();

/* Classes importeren */
include('Classes/User.php');
include('Classes/Template.php');

/* Includes importeren */
include('Includes/connect.php');

/* Classes initialiseren */
$cUser = new User();
$cTPL = new Template('Templates/main.tpl');

/* Verbinding met de database maken */
connectDB();

/* 'Inloggen' */
include('Includes/login.php');

$cTPL -> setPlace('TITEL', 'Inloggen');
$cTPL -> setFile('CONTENT', 'Templates/loginForm.tpl');

$cTPL -> show();
?>