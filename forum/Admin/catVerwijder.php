<?php

use Webdevils\Spelcodes\ExitException;

try {
    /* Header importeren */
    include('Includes/header.php');

    /* Controleren op catid */
    if (!isset($_GET['catid'])) {
        header('Http/1.0 404 Not Found');
        throw new ExitException();
    }

    /* Controleren of je bent ingelogd */
    if ((!$cUser->checkSession()) && (!$cUser->checkCookie())) {
        header('Location: ../../loginForm.php');
        throw new ExitException();
    }

    /* Permissie controleren */
    if (!$cUser->m_iPermis & 4) {
        die('Geen permissie...');
    }

    /* Controleren of het formulier is verzonden */
    if (isset($_POST['delete'])) {
        $sQuery = "SELECT count(forum_id) FROM forum_forums WHERE cat_id='" . add($_GET['catid']) . "';";
        if ($cResult = mysql_query($sQuery)) {
            if (mysql_result($cResult, 0) > 0) {
                $cTPL->setPlace('TITEL', 'Forum - Admin - Kon categorie niet verwijderen');
                $cTPL->setPlace('CONTENT', 'Er zitten nog fora in deze categorie, dus kon hij niet verwijderd worden');
            } else {
                $sQuery = "DELETE FROM forum_categories WHERE cat_id='" . add($_GET['catid']) . "';";
                if (mysql_query($sQuery)) {
                    header('Location: index.php');
                } else {
                    $cTPL->setPlace('TITEL', 'Fout met database');
                    $cTPL->setPlace('CONTENT', 'Door een fout met de database kon de categorie niet verwijderd worden');
                }
            }
        } else {
            $cTPL->setPlace('TITEL', 'Fout met database');
            $cTPL->setPlace('CONTENT', 'Door een fout met de database kon de categorie niet verwijderd worden');
        }
    } else {
        $cTPL->setPlace('TITEL', 'Forum - Admin - Categorie verwijderen');
        $cTPL->setFile('CONTENT', __DIR__ . '/Templates/catVerwijder.tpl');
        $cTPL->parse();

        $cTPL->setPlace('CATID', $_GET['catid']);
    }

    $cTPL->show();
} catch (ExitException $e) {}
