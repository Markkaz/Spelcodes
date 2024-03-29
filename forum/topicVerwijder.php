<?php

use Webdevils\Spelcodes\ExitException;

try {
    /* Header importeren */
    include('Includes/header.php');

    /* Controleren of er een topicid is */
    if (!isset($_GET['topicid'])) {
        header('Http/1.0 404 Not Found');
        throw new ExitException();
    }

    /* Controleren of je bent ingelogd */
    if (!$cUser->checkSession() && !$cUser->checkCookie()) {
        header('Location: ../loginForm.php');
        throw new ExitException();
    }

    /* Data ophalen */
    $sQuery = "SELECT forum_id, topic_poster FROM forum_topics WHERE topic_id='" . add($_GET['topicid']) . "';";
    if ($cResult = mysql_query($sQuery)) {
        $aData = mysql_fetch_assoc($cResult);

        /* Permissie controleren */
        if ($cUser->m_iUserid != $aData['topic_poster'] && !($cUser->m_iPermis & 2)) {
            header('Http/1.0 404 Not Found');
            throw new ExitException();
        }

        /* Controleren of het formulier is verzonden */
        if (isset($_POST['delete'])) {
            /* Berichten verwijderen */
            $sQuery = "DELETE FROM forum_posts WHERE topic_id='" . add($_GET['topicid']) . "';";
            if (mysql_query($sQuery)) {
                $sQuery = "DELETE FROM forum_topics WHERE topic_id='" . add($_GET['topicid']) . "';";
                if (mysql_query($sQuery)) {
                    header('Location: viewForum.php?forumid=' . $aData['forum_id']);
                    throw new ExitException();
                } else {
                    $cTPL->setPlace('TITEL', 'Fout met database');
                    $cTPL->setPlace('CONTENT', 'Door een fout met de database, zijn alleen de berichten uit het topic verwijderd.');
                }
            } else {
                $cTPL->setPlace('TITEL', 'Fout met database');
                $cTPL->setPlace('CONTENT', 'Door een fout met de database is je request niet verwerkt. Onze excuses voor het ongemak');
            }
        } else {
            $cTPL->setPlace('TITEL', 'Forum - Topic verwijderen');
            $cTPL->setFile('CONTENT', __DIR__ . '/Templates/topicVerwijder.tpl');
            $cTPL->parse();

            $cTPL->setPlace('TOPICID', $_GET['topicid']);
            $cTPL->setPlace('FORUMID', $aData['forum_id']);
        }
    } else {
        die('Geen permissie...');
    }

    $cTPL->show();
} catch (ExitException $e) {}
