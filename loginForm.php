<?php
error_reporting(E_ALL & ~E_DEPRECATED);

session_start();

/* Classes importeren */
include_once('Classes/User.php');
include_once('Classes/Template.php');

/* Includes importeren */
include_once('Includes/connect.php');

/* Classes initialiseren */
$cUser = new User();
$cTPL = new Template('Templates/main.tpl');

/* Verbinding met de database maken */
connectDB();

/* 'Inloggen' */
include('Includes/login.php');

$cTPL->setPlace('TITEL', 'Inloggen');
$cTPL->setFile('CONTENT', 'Templates/loginForm.tpl');

$cTPL->show();
