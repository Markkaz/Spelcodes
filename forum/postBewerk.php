<?php

use Webdevils\Spelcodes\ExitException;

try {
    /* Header importeren */
    include('Includes/header.php');

    /* Controleren of er een postid is meegegeven */
    if (!isset($_GET['postid'])) {
        header('Http/1.0 404 Not Found');
        throw new ExitException();
    }

    /* Controleren of je bent ingelogd */
    if ((!$cUser->checkSession()) && (!$cUser->checkCookie())) {
        header('Location: ../loginForm.php');
    }

    /* Alle data ophalen */
    $sQuery = "SELECT topic_id, post_poster, post_titel, post_text
           FROM forum_posts WHERE post_id='" . add($_GET['postid']) . "';";
    if ($cResult = mysql_query($sQuery)) {
        $aData = mysql_fetch_assoc($cResult);

        /* Permissie controleren */
        if ((!$cUser->m_iUserid == $aData['post_poster']) && (!$cUser->m_iPermis & 2)) {
            die('Geen permissie...');
        }

        /* Controleren of het formulier is verzonden */
        if (isset($_POST['titel'])) {
            $sQuery = "UPDATE forum_posts
               SET post_titel='" . add($_POST['titel']) . "', post_text='" . add($_POST['reactie']) . "'
               WHERE post_id='" . add($_GET['postid']) . "';";
            if (mysql_query($sQuery)) {
                header('Location: viewTopic.php?p=0&topicid=' . $aData['topic_id']);
            } else {
                $cTPL->setPlace('TITEL', 'Fout met de database');
                $cTPL->setPlace('CONTENT', 'Doordat er iets fout ging met de database is je request niet verwerkt. Onze excuses hiervoor.');
            }
        } else {
            $cTPL->setPlace('TITEL', 'Forum - Post bewerken');
            $cTPL->setFile('CONTENT', __DIR__ . '/Templates/postBewerk.tpl');
            $cTPL->parse();

            $cTPL->setPlace('TOPICID', $aData['topic_id']);
            $cTPL->setPlace('POSTID', $_GET['postid']);
            $cTPL->setPlace('TITEL', strip($aData['post_titel']));
            $cTPL->setPlace('BERICHT', strip($aData['post_text']));
        }
    } else {
        $cTPL->setPlace('TITEL', 'Fout met database');
        $cTPL->setPlace('CONTENT', 'Door een fout met de database is je request niet verwerkt. Onze excuses hiervoor.');
    }

    $cTPL->show();
} catch (ExitException $e) {}
