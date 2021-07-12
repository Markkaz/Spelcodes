<?php
namespace Webdevils\Spelcodes;

error_reporting(E_ALL & ~E_DEPRECATED);

session_start();

/* Classes importeren */
include_once('Classes/Template.php');
include_once('Classes/User.php');

/* Includes importeren */
include_once('Includes/connect.php');
include_once('Includes/slashes.php');

/* Mysql connectie maken */
connectDB();

/* Classes initialiseren */
$cUser = new \User();
$cTPL = new \Template('Templates/main.tpl');

if (($cUser->checkSession()) || ($cUser->checkCookie())) {
    $cTPL->setBlock('LOGIN', 'logout');
    if ($cUser->m_iPermis & 2) {
        $cTPL->setBlock('ADMIN', 'admin');
    }
} else {
    $cTPL->setBlock('LOGIN', 'login');
}

if (isset($_POST['username'])) {
    if ($_POST['password1'] == $_POST['password2']) {
        $sQuery = "SELECT userid FROM users WHERE username='" . add($_POST['username']) . "';";
        if ($cResult = mysql_query($sQuery)) {
            if (mysql_num_rows($cResult) <= 0) {
                $sQuery = "INSERT INTO users(username, password, email, ip, activate, permis, posts, datum)
                   VALUES ('', '" . add($_POST['username']) . "',
                  PASSWORD('" . add($_POST['password1']) . "'),
                           '" . add($_POST['email']) . "',
                           '', '0', '0', '0', NOW());";
                if (mysql_query($sQuery)) {
                    $cTPL->setPlace('TITEL', 'Succesvol geregistreerd');
                    $cTPL->setPlace('CONTENT', 'Je bent met succes geregistreerd als <b>' . $_POST['username'] . '</b>. Er is een email verzonden naar <b>' . $_POST['email']);
                    $cTPL->show();

                    $sBericht = "Beste, " . $_POST['username'] . ",\nJij of iemand anders heeft zich onder dit email adres aangemeld bij Spelcodes.\nJe login gegevens zijn:\nGebruikersnaam: " . $_POST['username'] . "\nWachtwoord: " . $_POST['password1'] . "\n\nKlik om je aanmelding compleet te maken op de volgende link:\n http://www.spelcodes.nl/reg.php?id=" . base64_encode(mysql_insert_id()) . "\n\nMet Vriendelijke Groeten,\n Spelcodes";

                    mail(trim($_POST['email']), 'Registratie bij Spelcodes', $sBericht);
                } else {
                    $cTPL->setPlace('TITEL', 'Fout bij het registreren');
                    $cTPL->setPlace('CONTENT', 'Sorry, maar er ging iets fout bij het registreren. Probeer het later opnieuw');
                    $cTPL->show();
                }
            } else {
                $cTPL->setPlace('TITEL', 'De gebruikersnaam bestaat al');
                $cTPL->setPlace('CONTENT', 'De gebruikersnaam waaronder je wil registreren is al in gebruik.');
                $cTPL->show();
            }
        } else {
            $cTPL->setPlace('TITEL', 'Fout bij het registreren');
            $cTPL->setPlace('CONTENT', 'Sorry, maar er ging iets fout bij het registreren. Probeer het later opnieuw');
            $cTPL->show();
        }
    } else {
        $cTPL->setPlace('TITEL', 'Fout bij het registreren');
        $cTPL->setPlace('CONTENT', 'Het tweede wachtwoord kwam niet overeen met het eerste');
        $cTPL->show();
    }
} else {
    $cTPL->setPlace('TITEL', 'Registreren');

    $cTPL->setFile('CONTENT', 'Templates/registreren.tpl');

    $cTPL->show();
}
