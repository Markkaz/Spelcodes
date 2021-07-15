<?php

use Webdevils\Spelcodes\ExitException;

try {
    /* Header importeren */
    include('Includes/header.php');

    /* Controleren of er wel een id is */
    if (!isset($_GET['topicid'])) {
        header('Http/1.0 404 Not Found');
        throw new ExitException();
    }

    /* Controleren of je bent ingelogd */
    if ((!$cUser->checkSession()) && (!$cUser->checkCookie())) {
        header('Location: ../loginForm.php');
    }

    /* Data ophalen */
    $sQuery = "SELECT forum_id, topic_titel, topic_poster FROM forum_topics WHERE topic_id='" . add($_GET['topicid']) . "';";
    if ($cResult = mysql_query($sQuery)) {
        $aData = mysql_fetch_assoc($cResult);
        if ((!$cUser->m_iUserid == $aData['topic_poster']) && (!$cUser->m_iPermis & 2)) {
            die('Geen permissie...');
        }

        /* Controleren of het formulier is verzonden */
        if (isset($_POST['titel'])) {
            $sQuery = "UPDATE forum_topics SET topic_titel='" . add($_POST['titel']) . "'
               WHERE topic_id='" . add($_GET['topicid']) . "';";
            if (mysql_query($sQuery)) {
                header('Location: viewForum.php?forumid=' . $aData['forum_id']);
            } else {
                $cTPL->setPlace('TITEL', 'Fout met database');
                $cTPL->setPlace('CONTENT', 'Door een fout met de database, is je request niet verwerkt.');
            }
        } else {
            $cTPL->setPlace('TITEL', 'Forum - Topic bewerken');
            $cTPL->setFile('CONTENT', __DIR__ . '/Templates/topicBewerk.tpl');
            $cTPL->parse();

            $cTPL->setPlace('TITEL', $aData['topic_titel']);
            $cTPL->setPlace('TOPICID', $_GET['topicid']);
            $cTPL->setPlace('FORUMID', $aData['forum_id']);
        }
    } else {
        die('Geen permissie...');
    }

    $cTPL->show();
} catch (ExitException $e) {}
