<?php
error_reporting(E_ALL & ~E_DEPRECATED);

session_start();

/* Classes importeren */
include_once('Classes/User.php');

/* Includes importeren */
include_once('Includes/connect.php');
include_once('Includes/slashes.php');

/* Classes initialiseren */
$cUser = new User();

/* Verbinding met de database maken */
connectDB();

try {
    if(!isset($_GET['id'])) {
        throw new Exception('Missing news id parameter');
    }

    if($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['reactie'])) {
        throw new Exception('Comment form isn\'t posted');
    }

    if (($cUser->checkSession()) || ($cUser->checkCookie())) {
        $sQuery = "INSERT INTO nieuwsreacties (nieuwsid, userid, bericht, datum, tijd)
         VALUES ('" . add($_GET['id']) . "', '" . $cUser->m_iUserid . "', 
         '" . add($_POST['reactie']) . "', NOW(), NOW());";
        if (!mysql_query($sQuery)) {
            throw new Exception('Error adding comment to the news item');
        }

        $cUser->addPost();
        header('Location: shownieuws.php?id=' . $_GET['id']);
    } else {
        header('Location: loginForm.php');
    }
} catch (Exception $e) {
    header('HTTP/1.0 404 Page not Found');
}