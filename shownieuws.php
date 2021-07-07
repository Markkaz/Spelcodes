<?php
error_reporting(E_ALL & ~E_DEPRECATED);

session_start();

/* Classes importeren */
include_once('Classes/User.php');
include_once('Classes/Template.php');

/* Includes importeren */
include_once('Includes/connect.php');
include_once('Includes/slashes.php');
include_once('Includes/smiley.php');
include_once('Includes/bbcode.php');

/* Classes initialiseren */
$cUser = new user();
$cTPL = new Template('Templates/main.tpl');

/* Verbinding met de database maken */
connectDB();

/* Controleren of er wel een id is meegegeven */
try {
    if(!isset($_GET['id'])) {
        throw new Exception('Missing id parameter');
    }

    include('Includes/login.php');

    $cTPL->setFile('CONTENT', 'Templates/shownieuws.tpl');
    $cTPL->parse();

    $sQuery = "SELECT n.titel, n.bericht, u.username, n.datum, n.tijd FROM nieuws n, users u
         WHERE u.userid = n.userid AND n.nieuwsid='" . add($_GET['id']) . "';";
    $cResult = mysql_query($sQuery);
    if(!$cResult) {
        throw new Exception('Error loading news item');
    }

    $aData = mysql_fetch_assoc($cResult);
    if(!$aData) {
        throw new Exception('News item doesn\'t exist');
    }

    $cTPL->setPlace('TITEL', add($aData['titel']));
    $cTPL->setPlace('USERNAME', add($aData['username']));
    $cTPL->setPlace('BERICHT', smiley(strip($aData['bericht'])));
    $cTPL->setPlace('DATUM', add($aData['datum'] . ' ' . $aData['tijd']));
    $cTPL->parse();

    $cTPL->setPlace('ID', $_GET['id']);

    $sQuery = "SELECT n.reactieid, n.bericht, u.userid, u.username, n.datum, n.tijd FROM nieuwsreacties n, users u
         WHERE n.userid=u.userid AND n.nieuwsid='" . add($_GET['id']) . "'
         ORDER BY n.datum, n.tijd;";
    $cResult = mysql_query($sQuery);
    if(!$cResult) {
        throw new Exception('Error loading news comments');
    }

    while ($aData = mysql_fetch_assoc($cResult)) {
        $cTPL->setBlock('REACTIES', 'reacties');
        $cTPL->parse();

        $cTPL->setPlace('AUTEUR', add($aData['username']));
        $cTPL->setPlace('MOMENT', add($aData['datum'] . ' ' . $aData['tijd']));
        $cTPL->setPlace('REACTIE', bbcode(smiley(strip_tags(strip($aData['bericht'])))));

        if (($cUser->m_iPermis & 2) || ($aData['userid'] == $cUser->m_iUserid)) {
            $cTPL->setBlock('REACTIEEDIT', 'edit');
            $cTPL->parse();
            $cTPL->setPlace('REACTIEID', $aData['reactieid']);
        } else {
            $cTPL->setPlace('REACTIEEDIT', '');
        }

        $cTPL->parse();
    }

    $cTPL->show();
} catch(Exception $e) {
    header('HTTP/1.0 404 Page not Found');
}
