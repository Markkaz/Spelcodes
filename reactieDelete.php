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
$cTPL = new Template('Templates/main.tpl');

/* Verbinding met mysql maken */
connectDB();

/* Permissies controleren */
if (isset($_GET['id']) && isset($_GET['topicid']) && isset($_GET['spelid'])) {
    if (($cUser->checkSession()) || ($cUser->checkCookie())) {
        $sQuery = "SELECT b.userid, b.topicid, b.bericht 
                    FROM berichten b
                    JOIN spellenhulp sh ON sh.topicid = b.topicid
                    WHERE b.berichtid='" . add($_GET['id']) . "' AND
                        b.topicid='" . add($_GET['topicid']) . "' AND 
                        sh.spelid='" . add($_GET['spelid']) . "';";
        if ($cResult = mysql_query($sQuery)) {
            $aData = mysql_fetch_assoc($cResult);
            if ($aData !== false && ($cUser->m_iPermis & 2 || $cUser->m_iUserid == $aData['userid'])) {
                /* Controleren of het formulier is verzonden */
                if (isset($_POST['delete'])) {
                    $sQuery = "DELETE FROM berichten WHERE berichtid='" . add($_GET['id']) . "';";
                    if (mysql_query($sQuery)) {
                        header('Location: gameview.php?id=' . $_GET['spelid'] . '&topicid=' . $aData['topicid']);
                    } else {
                        $cTPL->setPlace('TITEL', 'Fout met de database');
                        $cTPL->setPlace('CONTENT', 'Er is iets fout gegaan met de mysql database');
                        $cTPL->show();
                    }
                } else {
                    $cTPL->setPlace('TITEL', 'Bericht verwijderen');
                    $cTPL->setBlock('LOGIN', 'logout');
                    if ($cUser->m_iPermis & 2) {
                        $cTPL->parse();
                        $cTPL->setBlock('ADMIN', 'admin');
                    }
                    $cTPL->setFile('CONTENT', 'Templates/reactieDelete.tpl');
                    $cTPL->parse();

                    $cTPL->setPlace('ID', $_GET['id']);
                    $cTPL->setPlace('SPELID', $_GET['spelid']);
                    $cTPL->setPlace('TOPICID', $aData['topicid']);

                    $cTPL->show();
                }
            } else {
                header('HTTP/1.0 404');
            }
        } else {
            $cTPL->setPlace('TITEL', 'Fout met de database');
            $cTPL->setPlace('CONTENT', 'Er is iets fout gegaan met de mysql database');
            $cTPL->show();
        }
    } else {
        header('HTTP/1.0 404');
    }
} else {
    header('HTTP/1.0 404');
}
