<?php
error_reporting(E_ALL & ~E_DEPRECATED);

session_start();

/* Include files importeren */
include_once('Includes/connect.php');
include_once('Includes/slashes.php');

/* Classes importeren */
include_once('Classes/User.php');

if ((!isset($_POST['username'])) || (!isset($_POST['password']))) {
    header('HTTP/1.0 404 Page not Found');
}

/* User class initialiseren */
$cUser = new User();

/* Mysql verbinding maken */
connectDB();

if ((isset($_POST['cookie'])) && ($_POST['cookie'] == '1')) {
    $bCookie = true;
} else {
    $bCookie = false;
}

if ($cUser->login(add($_POST['username']), add($_POST['password']), $bCookie)) {
    if (isset($_GET['url'])) {
        if (isset($_GET['id'])) {
            header('Location: ' . $_GET['url'] . '?id=' . $_GET['id']);
        } else {
            header('Location: ' . $_GET['url']);
        }
    } else {
        header('Location: index.php');
    }
} else {
    /* Template parser importeren */
    include_once('Classes/Template.php');

    /* Template class initialiseren */
    $cTPL = new Template('Templates/main.tpl');

    $cTPL->setPlace('TITEL', 'Foutief wachtwoord/gebruikersnaam');
    $cTPL->setBlock('LOGIN', 'login');
    $cTPL->setPlace('CONTENT', 'Foutief wachtwoord/gebruikersnaam');

    $cTPL->parse();
    $cTPL->show();
}
