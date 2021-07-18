<?php

use Webdevils\Spelcodes\ExitException;

try {
    /* Header importeren */
    include('Includes/header.php');

    /* Controleren of het formulier wel verzonden is */
    if ((!isset($_POST['titel'])) || (!isset($_POST['order']))) {
        header('Http/1.0 404 Not Found');
        throw new ExitException();
    }

    /* Controleren of je bent ingelogd */
    if ((!$cUser->checkSession()) && (!$cUser->checkCookie())) {
        header('Location: ../../loginForm.php');
        throw new ExitException();
    }

    /* Permissie controleren */
    if ($cUser->m_iPermis & 4) {
        /* Formulier verwerken */
        $sQuery = "INSERT INTO forum_categories (cat_titel, cat_order)
           VALUES ('" . add($_POST['titel']) . "', '" . add($_POST['order']) . "');";
        if (mysql_query($sQuery)) {
            header('Location: index.php');
        } else {
            $cTPL->setPlace('TITEL', 'Fout met database');
            $cTPL->setPlace('CONTENT', 'Door een fout met de database, is je categorie niet toegevoegd.');
            $cTPL->show();
        }
    } else {
        header('Http/1.0 404 Not Found');
    }
} catch (ExitException $e) {}