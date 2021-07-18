<?php

use Webdevils\Spelcodes\ExitException;

try {
    /* Header importeren */
    include('Includes/header.php');

    /* Controleren of er wel een catid is */
    if (!isset($_GET['catid'])) {
        header('Http/1.0 404 Not Found');
        throw new ExitException();
    }

    /* Controleren of je wel bent ingelogd */
    if ((!$cUser->checkSession()) && (!$cUser->checkCookie())) {
        header('Location: ../../loginForm.php');
        throw new ExitException();
    }

    /* Permissie controleren */
    if (!$cUser->m_iPermis & 4) {
        die('Geen permissie...');
    }

    $sql = 'SELECT EXISTS(SELECT * FROM forum_categories WHERE cat_id = ' . add($_GET['catid']) . ') as cat_exists;';
    $result = mysql_query($sql);
    if(!$result) {
        header('Http/1.0 500 Internal Server Error');
        throw new ExitException();
    }

    $data = mysql_fetch_assoc($result);
    if($data === false || !$data['cat_exists']) {
        header('Http/1.0 404 Not Found');
        throw new ExitException();
    }

    /* Controleren of het formulier is verzonden */
    if (isset($_POST['titel']) && isset($_POST['order'])) {
        $sQuery = "UPDATE forum_categories
             SET cat_titel='" . add($_POST['titel']) . "', cat_order='" . add($_POST['order']) . "'
             WHERE cat_id='" . add($_GET['catid']) . "';";
        if (mysql_query($sQuery)) {
            header('Location: index.php');
            throw new ExitException();
        } else {
            $cTPL->setPlace('TITEL', 'Fout met database');
            $cTPL->setPlace('CONTENT', 'Door een fout met de database is de categorie niet bewerkt.');
        }
    } else {
        $cTPL->setPlace('TITEL', 'Forum - Admin - Categorie bewerken');
        $cTPL->setFile('CONTENT', __DIR__ . '/Templates/catBewerk.tpl');
        $cTPL->parse();

        /* Data ophalen */
        $sQuery = "SELECT cat_titel, cat_order FROM forum_categories WHERE cat_id='" . add($_GET['catid']) . "';";
        if ($cResult = mysql_query($sQuery)) {
            $aData = mysql_fetch_assoc($cResult);
            $cTPL->setPlace('TITEL', $aData['cat_titel']);
            $cTPL->setPlace('ORDER', $aData['cat_order']);
        }
        $cTPL->setPlace('CATID', $_GET['catid']);
    }

    $cTPL->show();
} catch (ExitException $e) {}
