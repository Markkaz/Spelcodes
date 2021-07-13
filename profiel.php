<?php

use Webdevils\Spelcodes\ExitException;

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

/* Inloggen */
include('Includes/login.php');

try {


    if (!(($cUser->checkSession()) || ($cUser->checkCookie()))) {
        header('Http/1.0 404 Not Found');
    } else {
        /* Controleren of het formulier is verzonden */
        if (isset($_POST['username']) && isset($_POST['wachtwoord'])) {
            $sQuery = "SELECT userid FROM users WHERE username='" . add($_POST['username']) . "' AND password=SHA2('" . add($_POST['wachtwoord']) . "', 0);";
            if ($cResult = mysql_query($sQuery)) {
                if (mysql_num_rows($cResult) <= 0) {
                    header('Location: profiel.php?error=Je wachtwoord en/of gebruikersnaam klopt niet');
                    throw new ExitException();
                }
                $aData = mysql_fetch_assoc($cResult);
                if ($aData['userid'] != $cUser->m_iUserid) {
                    header('Location: profiel.php?error=Je wachtwoord en/of gebruikersnaam klopt niet');
                    throw new ExitException();
                }

                /* Alle controles zijn uitgevoerd, nu kijken of de persoon wachtwoord wil veranderen */
                if (!empty($_POST['wachtwoord_nieuw1']) && !empty($_POST['wachtwoord_nieuw2'])) {
                    if ($_POST['wachtwoord_nieuw1'] != $_POST['wachtwoord_nieuw2']) {
                        header('Location: profiel.php?error=De twee nieuwe wachtwoorden komen niet overeen');
                        throw new ExitException();
                    }
                    /* Het nieuwe wachtwoord opslaan */
                    $sQuery = "UPDATE users SET password=SHA2('" . add($_POST['wachtwoord_nieuw1']) . "', 0) WHERE userid='" . add($cUser->m_iUserid) . "';";
                    if (!mysql_query($sQuery)) {
                        header('Location: profiel.php?error=Er is een probleem met de database');
                        throw new ExitException();
                    }
                }

                /* Controleren of de persoon zijn email wil veranderen */
                if (!empty($_POST['email'])) {
                    $sQuery = "SELECT userid FROM users WHERE email='" . add($_POST['email']) . "';";
                    if (!$cResult = mysql_query($sQuery)) {
                        header('Location: profiel.php?error=Er is een probleem met de database');
                        throw new ExitException();
                    }
                    if (mysql_num_rows($cResult) > 0) {
                        header('Location: profiel.php?error=Het email adres is al in gebruik');
                        throw new ExitException();
                    }

                    $sQuery = "UPDATE users SET email='" . add($_POST['email']) . "', activate=0 WHERE userid='" . add($cUser->m_iUserid) . "';";
                    if (!mysql_query($sQuery)) {
                        header('Location: profiel.php?error=Er is een probleem met de database');
                        throw new ExitException();
                    } else {
                        $sQuery = "SELECT username FROM users WHERE userid='" . add($cUser->m_iUserid) . "';";
                        if ($cResult = mysql_query($sQuery)) {
                            $aData = mysql_fetch_assoc($cResult);

                            $sSubject = 'Verandering van email bij Spelcodes';
                            $sBericht = "Beste, " . strip($aData['username']) . "\nJij of iemand anders heeft het email adres bij zijn account veranderd naar " . add($_POST['email']) . ".\nJe account is door deze verandering tijdelijk gedeactiveerd. Klik op de volgende link om het weer te activeren:\nhttp://www.spelcodes.nl/reg.php?id=" . base64_encode($cUser->m_iUserid) . "\n\nMet vriendelijke groeten,\nHet webmaster team van Spelcodes";
                            mail(trim($_POST['email']), $sSubject, $sBericht);
                        } else {
                            header('Location: profiel.php?error=Er is iets fout gegaan bij het verzenden van een herregistratie mail');
                        }
                    }
                }

                /* Terug naar profiel pagina sturen */
                header('Location: profiel.php?error=Je profiel is met succes gewijzigd');
                throw new ExitException();
            } else {
                header('Location: profiel.php?error=Er is een probleem met de database');
                throw new ExitException();
            }
        } else {
            $cTPL->setPlace('TITEL', 'Profiel bewerken');
            $cTPL->setFile('CONTENT', 'Templates/profiel.tpl');
            $cTPL->parse();

            if (isset($_GET['error'])) {
                $cTPL->setPlace('ERROR', $_GET['error']);
            }

            $sQuery = "SELECT username FROM users WHERE userid='" . add($cUser->m_iUserid) . "';";
            if ($cResult = mysql_query($sQuery)) {
                $aData = mysql_fetch_assoc($cResult);
                $cTPL->setPlace('USERNAME', $aData['username']);
            }
        }

        $cTPL->show();
    }
} catch (ExitException $e) {}