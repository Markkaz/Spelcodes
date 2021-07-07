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

/* Verbinding met de database maken */
connectDB();

$cTPL->setPlace('TITEL', 'Link partners');

/* Controleren of je bent ingelogd en of je admin bent */
if (($cUser->checkSession()) || ($cUser->checkCookie())) {
    $cTPL->setBlock('LOGIN', 'logout');
    $cTPL->setBlock('FORUMLINKS', 'forumingelogd');

    if (2047 & $cUser->m_iPermis) {
        $cTPL->parse();
        $cTPL->setBlock('ADMIN', 'admin');
        $cTPL->setBlock('FORUMADMIN', 'forumadmin');
    }
} else {
    $cTPL->setBlock('LOGIN', 'login');
    $cTPL->parse();
    $cTPL->setPlace('THISPAGE', $_SERVER['PHP_SELF']);
    $cTPL->setBlock('FORUMLINKS', 'forumnormaal');
}

/*De pagina zelf */
$cTPL->setFile('CONTENT', 'Templates/links.tpl');
$cTPL->parse();

$sQuery = "SELECT linkid, link, incomming, outcomming FROM links ORDER BY link;";
if ($cResult = mysql_query($sQuery)) {
    $sBG = '';
    while ($aData = mysql_fetch_assoc($cResult)) {
        if ($sBG == '') {
            $sBG = 'img/patroon.gif';
        } else {
            $sBG = '';
        }
        $cTPL->setBlock('LINK', 'link');
        $cTPL->parse();

        $cTPL->setPlace('ID', $aData['linkid']);
        $cTPL->setPlace('LINKNAAM', $aData['link']);
        $cTPL->setPlace('IN', $aData['incomming']);
        $cTPL->setPlace('UIT', $aData['outcomming']);
        $cTPL->setPlace('BG', $sBG);
        $cTPL->parse();
    }
}

$cTPL->show();
