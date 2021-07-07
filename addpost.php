<?php
error_reporting(E_ALL & ~E_DEPRECATED);

session_start();

/* Classes importeren */
include_once('Classes/User.php');
include_once('Classes/Template.php');

/* Includes importeren */
include_once('Includes/connect.php');
include_once('Includes/slashes.php');

/* Classes initialiseren */
$cUser = new User();

/* Verbinden met mysql */
connectDB();

/* Permissies controleren */
if ((isset($_GET['topicid'])) && (isset($_POST['reactie'])) && (isset($_GET['id']))) {
    if (($cUser->checkSession()) || ($cUser->checkCookie())) {
        $sQuery = "INSERT INTO berichten (berichtid, topicid, userid, bericht, datum, tijd)
               VALUES ('', '" . add($_GET['topicid']) . "', '" . $cUser->m_iUserid . "',
               '" . add($_POST['reactie']) . "', NOW(), NOW());";
        if (mysql_query($sQuery)) {
            $cUser->addPost();
            header('Location: gameview.php?id=' . $_GET['id'] . '&topicid=' . $_GET['topicid']);
        } else {
            print 'Er is iets niet in orde met de database';
        }
    } else {
        header('Location: loginForm.php');
    }
} else {
    header('HTTP/1.0 404 Page not Found');
}