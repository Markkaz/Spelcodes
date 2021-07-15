<?php

use Webdevils\Spelcodes\ExitException;

try {
    /* Header importeren */
    include('Includes/header.php');

    /* Controleren of er wel een postid is */
    if (!isset($_GET['postid'])) {
        header('Http/1.0 404 Not Found');
        throw new ExitException();
    }

    /* Controleren of de user is ingelogd */
    if ((!$cUser->checkSession()) && (!$cUser->checkCookie())) {
        header('Location: ../loginForm.php');
    }

    /* Permissie controleren */
    $sQuery = "SELECT topic_id, post_poster FROM forum_posts WHERE post_id='" . add($_GET['postid']) . "';";
    if ($cResult = mysql_query($sQuery)) {
        $aData = mysql_fetch_assoc($cResult);
        if ((!$aData['post_poster'] == $cUser->m_iUserid) && (!$cUser->m_iPermis & 2)) {
            die('Geen permissie...');
        }

        /* Controleren of het formulier is verzonden */
        if (isset($_POST['delete'])) {
            $sQuery = "DELETE FROM forum_posts WHERE post_id='" . add($_GET['postid']) . "';";
            if (mysql_query($sQuery)) {
                header('Location: viewTopic.php?p=0&topicid=' . $aData['topic_id']);
            } else {
                $cTPL->setPlace('TITEL', 'Fout met database');
                $cTPL->setPlace('CONTENT', 'Door een fout met de database is je request niet verwerkt. Onze excuses voor het ongemak.');
            }
        } else {
            $cTPL->setPlace('TITEL', 'Forum - Post verwijderen');
            $cTPL->setFile('CONTENT', __DIR__ . '/Templates/postVerwijder.tpl');
            $cTPL->parse();

            $cTPL->setPlace('TOPICID', $aData['topic_id']);
            $cTPL->setPlace('POSTID', $_GET['postid']);
        }
    } else {
        die('Geen permissie...');
    }

    $cTPL->show();
} catch (ExitException $e) {}
