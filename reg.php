<?php
error_reporting(E_ALL & ~E_DEPRECATED);

/* Template class includen */
include_once('Classes/Template.php');

/* Includes importeren */
include_once('Includes/connect.php');
include_once('Includes/slashes.php');

/* Template initialiseren */
$cTPL = new Template('Templates/main.tpl');

/* Verbinding maken met database */
connectDB();

if (isset($_GET['id'])) {
    $sql = "SELECT activate FROM users WHERE userid='".add(base64_decode($_GET['id']))."';";
    $result = mysql_query($sql);
    if($result && $data = mysql_fetch_assoc($result)) {
        if($data['activate'] == '0') {
            $sQuery = "UPDATE users SET activate='1' WHERE userid='" . add(base64_decode($_GET['id'])) . "';";
            if (mysql_query($sQuery)) {
                $cTPL->setPlace('TITEL', 'Account geactiveerd');
                $cTPL->setPlace('CONTENT', 'Je account is met succes geactiveerd. Je kan nu inloggen via het login formulier.');
                $cTPL->setBlock('LOGIN', 'login');
                $cTPL->show();
            } else {
                $cTPL->setPlace('TITEL', 'Activering mislukt');
                $cTPL->setPlace('CONTENT', 'Er is iets fout gegaan tijdens het activeren van je account. Probeer het later opnieuw');
                $cTPL->setBlock('LOGIN', 'login');
                $cTPL->show();
            }
        } else {
            $cTPL->setPlace('TITEL', 'Activering mislukt');
            $cTPL->setPlace('CONTENT', 'Dit account is al geactiveerd.');
            $cTPL->setBlock('LOGIN', 'login');
            $cTPL->show();
        }
    } else {
        header('HTTP/1.0 404');
    }
} else {
    header('HTTP/1.0 404');
}
