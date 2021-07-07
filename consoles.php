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

/* Mysql connectie maken */
connectDB();

if (isset($_GET['id'])) {
    include('Includes/login.php');

    $cTPL->setFile('CONTENT', 'Templates/consoles.tpl');
    $cTPL->parse();

    $sQuery = "SELECT naam FROM consoles WHERE consoleid='" . add($_GET['id']) . "';";
    if ($cResult = mysql_query($sQuery)) {
        $aData = mysql_fetch_assoc($cResult);
        $cTPL->setPlace('NAAMCONSOLE', $aData['naam']);
        $cTPL->setPlace('TITEL', $aData['naam']);
    }
    $cTPL->setPlace('ID', $_GET['id']);

    /* Controleren of er een letter is geselecteerd */
    if (isset($_GET['letter'])) {
        $cTPL->setBlock('CONTENT', 'spellen');
        /* Controleren of deze letter een # is */
        if ($_GET['letter'] == '#') {
            $sQuery = "SELECT spelid, naam, rating, stemmen FROM spellen WHERE consoleid='" . add($_GET['id']) . "'
                 AND (naam LIKE '0%' OR
                     naam LIKE '1%' OR
                     naam LIKE '2%' OR
                     naam LIKE '3%' OR
                     naam LIKE '4%' OR
                     naam LIKE '5%' OR
                     naam LIKE '6%' OR
                     naam LIKE '7%' OR
                     naam LIKE '8%' OR
                     naam LIKE '9%') ORDER BY naam";
        } else {
            $sQuery = "SELECT spelid, naam, rating, stemmen FROM spellen
                 WHERE consoleid='" . add($_GET['id']) . "' AND naam LIKE '" . add($_GET['letter']) . "%'
                 ORDER BY naam;";
        }
        if ($cResult = mysql_query($sQuery)) {
            $sKleur = '';
            while ($aData = mysql_fetch_assoc($cResult)) {
                if ($sKleur == '') {
                    $sKleur = 'img/patroon.gif';
                } else {
                    $sKleur = '';
                }
                /* Data verwerken */
                $cTPL->setBlock('SPEL', 'spel');
                $cTPL->parse();

                $cTPL->setPlace('SPELID', strip($aData['spelid']));
                $cTPL->setPlace('NAAM', strip($aData['naam']));
                $cTPL->setPlace('KLEUR', $sKleur);
                $cTPL->parse();

                for ($iTeller = 0; $iTeller < 5; $iTeller++) {
                    if ($aData['stemmen'] == 0) {
                        $cTPL->setPlace('STER' . ($iTeller + 1), 'legester.gif');
                    } else {
                        $iRating = round(($aData['rating'] / $aData['stemmen']) * 2) / 2;
                        if (($iRating - $iTeller) > 0) {
                            if (($iRating - $iTeller) == 0.5) {
                                $cTPL->setPlace('STER' . ($iTeller + 1), 'halvester.gif');
                            } else {
                                $cTPL->setPlace('STER' . ($iTeller + 1), 'helester.gif');
                            }
                        } else {
                            $cTPL->setPlace('STER' . ($iTeller + 1), 'legester.gif');
                        }
                    }
                }
                $cTPL->parse();
            }
        }

        $cTPL->setPlace('LETTER', '(' . $_GET['letter'] . ')');
    } else {
        /* Er is geen letter geselecteerd */
        $cTPL->setBlock('PLAATJE', 'plaatjes');
        $cTPL->parse();

        /* Plaatjes neerzetten */
        $sQuery = "SELECT s.spelid, s.naam, c.naam AS console, s.map FROM spellen s, consoles c, spellenview sv
               WHERE c.consoleid = sv.consoleid AND c.consoleid=s.consoleid AND s.spelid=sv.spelid
               AND s.consoleid='" . add($_GET['id']) . "' ORDER BY RAND() LIMIT 0,3;";
        if ($cResult = mysql_query($sQuery)) {
            $iTeller = 0;
            while ($aData = mysql_fetch_assoc($cResult)) {
                $iTeller++;
                $cTPL->setPlace('CONSOLE' . $iTeller, strip($aData['console']));
                $cTPL->setPlace('MAP' . $iTeller, strip($aData['map']));
                $cTPL->setPlace('NAAMSPEL' . $iTeller, strip($aData['naam']));
                $cTPL->setPlace('SPELID' . $iTeller, strip($aData['spelid']));
                $cTPL->parse();
            }
        }

        /* De tien laatst toegevoegde spellen weergeven */
        $cTPL->setBlock('CONTENT', 'spellen');
        $cTPL->parse();
        $sQuery = "SELECT spelid, naam, rating, stemmen FROM spellen WHERE consoleid='" . add($_GET['id']) . "' 
               ORDER BY spelid DESC LIMIT 0, 10;";
        if ($cResult = mysql_query($sQuery)) {
            $sKleur = '';
            while ($aData = mysql_fetch_assoc($cResult)) {
                if ($sKleur == '') {
                    $sKleur = 'img/patroon.gif';
                } else {
                    $sKleur = '';
                }
                $cTPL->setBlock('SPEL', 'spel');
                $cTPL->parse();

                $cTPL->setPlace('NAAM', strip($aData['naam']));
                $cTPL->setPlace('SPELID', strip($aData['spelid']));
                $cTPL->setPlace('KLEUR', $sKleur);

                /* Sterren goedzetten */
                for ($iTeller = 0; $iTeller < 5; $iTeller++) {
                    if ($aData['stemmen'] > 0) {
                        $iRating = round(($aData['rating'] / $aData['stemmen']) * 2) / 2;
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
            }
        }
    }

    $cTPL->show();
} else {
    header('HTTP/1.0 404 Page not Found');
}