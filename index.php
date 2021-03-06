<?php
error_reporting(E_ALL & ~E_DEPRECATED);

session_start();

/* Inlude files importeren */
include_once('Includes/connect.php');
include_once('Includes/slashes.php');
include_once('Includes/smiley.php');

/* Classes importeren */
include_once('Classes/User.php');
include_once('Classes/Template.php');

/* Classes initialiseren */
$cUser = new User();
$cTPL = new Template('Templates/main.tpl');

/* Connectie met mysql maken */
connectDB();

include('Includes/login.php');

$cTPL->setPlace('TITEL', 'Home');

$cTPL->setFile('CONTENT', 'Templates/index.tpl');
$cTPL->parse();

$iTeller = 1;
$sQuery = "SELECT s.spelid, s.naam, c.naam AS console, s.map FROM spellen s, spellenview sv, consoles c
           WHERE s.spelid=sv.spelid AND c.consoleid=s.consoleid ORDER BY RAND() LIMIT 0,3;";

if ($cResult = mysql_query($sQuery)) {
    while ($aData = mysql_fetch_assoc($cResult)) {
        $cTPL->setPlace('CONSOLE' . $iTeller, $aData['console']);
        $cTPL->setPlace('MAP' . $iTeller, $aData['map']);
        $cTPL->setPlace('NAAM' . $iTeller, strip($aData['naam']));
        $cTPL->setPlace('ID' . $iTeller, $aData['spelid']);
        $cTPL->parse();

        $iTeller++;
    }
}

$sQuery = "SELECT n.nieuwsid, n.titel, n.bericht, u.username, n.datum, n.tijd FROM nieuws n, users u
           WHERE n.userid=u.userid ORDER BY n.datum DESC, n.tijd DESC LIMIT 0,5;";

if ($cResult = mysql_query($sQuery)) {
    while ($aData = mysql_fetch_assoc($cResult)) {
        $cTPL->setBlock('NIEUWS', 'nieuws');
        $cTPL->parse();

        $cTPL->setPlace('NIEUWSID', add($aData['nieuwsid']));
        $cTPL->setPlace('TITEL', add($aData['titel']));
        $cTPL->setPlace('BERICHT', smiley(strip($aData['bericht'])));
        $cTPL->setPlace('USERNAME', add($aData['username']));
        $cTPL->setPlace('DATUM', add($aData['datum'] . ' ' . $aData['tijd']));
        $cTPL->parse();

        $sQuery = "SELECT reactieid FROM nieuwsreacties WHERE nieuwsid='" . add($aData['nieuwsid']) . "';";

        if ($cNumberRowsResult = mysql_query($sQuery)) {
            $cTPL->setPlace('REACTIES', mysql_num_rows($cNumberRowsResult));
        }
    }
}

$cTPL->show();
