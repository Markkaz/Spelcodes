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

/* Verbinding met database maken */
connectDB();

/* Permissie controleren */
if (($cUser->checkSession() || $cUser->checkCookie()) && isset($_GET['id'])) {
    $sQuery = "SELECT userid FROM nieuwsreacties WHERE reactieid='" . add($_GET['id']) . "';";
    if ($cResult = mysql_query($sQuery)) {
        $aData = mysql_fetch_assoc($cResult);
        if($aData) {
            if (($cUser->m_iPermis & 2) || ($cUser->m_iUserid == $aData['userid'])) {
                /* Controleren of het formulier verzonden is */
                if (isset($_POST['delete'])) {
                    $sQuery = "SELECT nieuwsid FROM nieuwsreacties WHERE reactieid='" . add($_GET['id']) . "';";
                    if ($cResult = mysql_query($sQuery)) {
                        $sQuery = "DELETE FROM nieuwsreacties WHERE reactieid='" . add($_GET['id']) . "';";
                        if (mysql_query($sQuery)) {
                            $aData = mysql_fetch_assoc($cResult);
                            header('Location: shownieuws.php?id=' . $aData['nieuwsid']);
                        } else {
                            $cTPL->setPlace('TITEL', 'Fout met database');
                            $cTPL->setPlace('CONTENT', 'Er is iets fout gegaan met de database');
                            $cTPL->show();
                        }
                    } else {
                        $cTPL->setPlace('TITEL', 'Fout met database');
                        $cTPL->setPlace('CONTENT', 'Er is iets fout gegaan met de database');
                        $cTPL->show();
                    }
                } else {
                    $cTPL->setPlace('TITEL', 'Reactie verwijderen');
                    $cTPL->setBlock('LOGIN', 'logout');
                    $cTPL->parse();

                    if ($cUser->m_iPermis & 2) {
                        $cTPL->setBlock('ADMIN', 'admin');
                    }

                    $cTPL->setFile('CONTENT', 'Templates/nieuwsDelete.tpl');
                    $cTPL->parse();

                    $sQuery = "SELECT nieuwsid FROM nieuwsreacties WHERE reactieid='" . add($_GET['id']) . "';";
                    if ($cResult = mysql_query($sQuery)) {
                        $aData = mysql_fetch_assoc($cResult);
                        $cTPL->setPlace('NIEUWSID', $aData['nieuwsid']);
                    }
                    $cTPL->setPlace('ID', $_GET['id']);

                    $cTPL->show();
                }
            } else {
                header('HTTP/1.0 404');
            }
        } else {
            header('hTTP/1.0 404');
        }
    } else {
        print 'Er is iets fout gegaan met de mysql database';
    }
} else {
    header('HTTP/1.0 404');
}
