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
$cUser = new User();
$cTPL = new Template('Templates/main.tpl');

/* Mysql connectie maken */
connectDB();

if (isset($_GET['id'])) {
    $cTPL->setPlace('TITEL', 'Spel - Topics overzicht');

    include('Includes/login.php');

    $cTPL->setFile('CONTENT', 'Templates/spelinfo.tpl');
    $cTPL->parse();

    $sQuery = "SELECT s.naam, c.naam AS console, s.map, s.developer, s.publisher, s.developerurl, s.publisherurl, s.stemmen, s.rating
             FROM spellen s, consoles c WHERE s.spelid='" . add($_GET['id']) . "' AND s.consoleid=c.consoleid;";

    if ($cResult = mysql_query($sQuery)) {
        $aData = mysql_fetch_assoc($cResult);

        $cTPL->setPlace('NAAMSPEL', strip($aData['naam']));
        $cTPL->setPlace('DISPLAY', $aData['map']);
        $cTPL->setPlace('CONSOLE', $aData['console']);
        $cTPL->setPlace('DEVELOPER', strip($aData['developer']));
        $cTPL->setPlace('DEVELOPERURL', $aData['developerurl']);
        $cTPL->setPlace('PUBLISHER', strip($aData['publisher']));
        $cTPL->setPlace('PUBLISHERURL', $aData['publisherurl']);

        for ($iTeller = 0; $iTeller <= 4; $iTeller++) {
            if ($aData['stemmen'] > 0) {
                $iRating = (round(($aData['rating'] / $aData['stemmen']) * 2)) / 2;
                if (($iRating - $iTeller) > 0) {
                    if (($iRating - $iTeller) == 0.5) {
                        $cTPL->setPlace('STER' . ($iTeller + 1), 'halvester.gif');
                    } else {
                        $cTPL->setPlace('STER' . ($iTeller + 1), 'helester.gif');
                    }
                } else {
                    $cTPL->setPlace('STER' . ($iTeller + 1), 'legester.gif');
                }
            } else {
                $cTPL->setPlace('STER' . ($iTeller + 1), 'legester.gif');
            }
        }

        /*Stemformulier */
        $sQuery = "SELECT spelid FROM stemmen 
               WHERE spelid='" . $_GET['id'] . "' AND ip='" . $_SERVER['REMOTE_ADDR'] . "';";
        if ($cResult = mysql_query($sQuery)) {
            if (mysql_num_rows($cResult) > 0) {
                $cTPL->setPlace('STEMFORMULIER', '');
            } else {
                $cTPL->setBlock('STEMFORMULIER', 'stemformulier');
                $cTPL->parse();
                $cTPL->setPlace('SPELID', $_GET['id']);
            }
        }
    }

    $sQuery = "SELECT t.topicid, t.titel FROM topics t, spellenhulp s
             WHERE s.spelid='" . add($_GET['id']) . "' AND t.topicid=s.topicid;";
    if ($cResult = mysql_query($sQuery)) {
        $sKleur = '';
        while ($aData = mysql_fetch_assoc($cResult)) {
            if ($sKleur == '') {
                $sKleur = 'img/patroon.gif';
            } else {
                $sKleur = '';
            }

            $cTPL->setBlock('TOPIC', 'topic');
            $cTPL->parse();

            $cTPL->setPlace('TITEL', strip($aData['titel']));
            $cTPL->setPlace('TOPICID', $aData['topicid']);
            $cTPL->setPlace('ID', $_GET['id']);
            $cTPL->setPlace('KLEUR', $sKleur);
            $cTPL->parse();
        }
    }

    $cTPL->setFile('CONTENT', 'Templates/gameview.tpl');
    $cTPL->parse();

    if (isset($_GET['topicid'])) {
        $cTPL->setBlock('CONTENT', 'topicview');
        $cTPL->setPlace('TOPICID', $_GET['topicid']);
        $cTPL->setPlace('ID', $_GET['id']);

        $sQuery = "SELECT t.titel, t.bericht, u.username, t.datum, t.tijd FROM topics t, users u 
               WHERE topicid='" . add($_GET['topicid']) . "' AND t.userid=u.userid;";
        if ($cResult = mysql_query($sQuery)) {
            $aData = mysql_fetch_assoc($cResult);
            $cTPL->setPlace('TITEL', strip($aData['titel']));
            $cTPL->setPlace('TEXT', smiley(strip($aData['bericht'])));
            $cTPL->setPlace('AUTEUR', $aData['username']);
            $cTPL->setPlace('DATUM', $aData['datum'] . ' ' . $aData['tijd']);
            $cTPL->parse();
        }

        $sQuery = "SELECT b.berichtid, b.bericht, b.userid, u.username, b.datum, b.tijd FROM users u, berichten b
               WHERE b.userid=u.userid AND b.topicid='" . add($_GET['topicid']) . "' ORDER BY b.datum, b.tijd;";
        if ($cResult = mysql_query($sQuery)) {
            while ($aData = mysql_fetch_assoc($cResult)) {
                $cTPL->setBlock('COMMENTAAR', 'reactie');
                $cTPL->parse();

                $cTPL->setPlace('USERNAME', $aData['username']);
                $cTPL->setPlace('DATUMREACTIE', $aData['datum'] . ' ' . $aData['tijd']);
                $cTPL->setPlace('BERICHT', smiley(bbcode(strip(strip_tags($aData['bericht'])))));

                if (($cUser->m_iPermis & 2) || ($cUser->m_iUserid == $aData['userid'])) {
                    $cTPL->setBlock('REACTIEEDIT', 'edit');
                    $cTPL->parse();

                    $cTPL->setPlace('BERICHTID', $aData['berichtid']);
                    $cTPL->setPlace('SPELID', $_GET['id']);
                } else {
                    $cTPL->setPlace('REACTIEEDIT', '');
                }

                $cTPL->parse();
            }
        }
    } else {
    }

    $cTPL->show();
} else {
    header('HTTP/1.0 404 Page not Found');
}
