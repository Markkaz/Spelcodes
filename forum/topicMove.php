<?php

use Webdevils\Spelcodes\ExitException;

try {
    /* Header importeren */
    include('Includes/header.php');

    /* Controleren of er een topicid is */
    if ((!isset($_GET['topicid']))) {
        header('Http/1.0 404 Not Found');
        throw new ExitException();
    }

    /* Controleren of je bent ingelogd */
    if ((!$cUser->checkSession()) && (!$cUser->checkCookie())) {
        header('Location: ../loginForm.php');
        throw new ExitException();
    }

    /* Permissie controleren */
    if (!($cUser->m_iPermis & 2)) {
        header('Http/1.0 404 Not Found');
        throw new ExitException();
    }

    $sql = 'SELECT topic_id, forum_id, topic_titel FROM forum_topics WHERE topic_id = '.add($_GET['topicid']);
    $result = mysql_query($sql);
    if(!$result) {
        $cTPL->setPlace('TITEL', 'Fout met database');
        $cTPL->setPlace('CONTENT', 'Door een fout met de database, is je request niet verwerkt.');
        $cTPL->show();
        throw new ExitException();
    }

    $topic = mysql_fetch_assoc($result);
    if($topic === false) {
        header('Http/1.0 404 Not Found');
        throw new ExitException();
    }

    /* Controleren of het formulier is verzonden */
    if (isset($_POST['forumid'])) {
        $sQuery = "UPDATE forum_topics SET forum_id='" . add($_POST['forumid']) . "'
             WHERE topic_id='" . $topic['topic_id'] . "';";
        if (mysql_query($sQuery)) {
            header('Location: viewForum.php?forumid=' . $_POST['forumid']);
            throw new ExitException();
        } else {
            $cTPL->setPlace('TITEL', 'Fout met database');
            $cTPL->setPlace('CONTENT', 'Door een fout met de database, is je request niet verwerkt.');
        }
    } else {
        $cTPL->setPlace('TITEL', 'Forum - Verplaats topic');
        $cTPL->setFile('CONTENT', __DIR__ . '/Templates/topicMove.tpl');
        $cTPL->parse();

        $cTPL->setPlace('TOPIC_TITEL', $topic['topic_titel']);

        /* Fora ophalen */
        $sQuery = "SELECT forum_id, forum_titel FROM forum_forums ORDER BY forum_titel;";
        if ($cResult = mysql_query($sQuery)) {
            while ($aData = mysql_fetch_assoc($cResult)) {
                $cTPL->setBlock('OPTION', 'option');
                $cTPL->parse();

                $cTPL->setPlace('OPTIONFORUMID', $aData['forum_id']);
                $cTPL->setPlace('FORUMNAAM', $aData['forum_titel']);

                if ($aData['forum_id'] == $topic['forum_id']) {
                    $cTPL->setPlace('SELECTED', ' "selected"');
                }
                $cTPL->parse();
            }
        }
        $cTPL->setPlace('FORUMID', $topic['forum_id']);
        $cTPL->setPlace('TOPICID', $topic['topic_id']);
    }

    $cTPL->show();
} catch (ExitException $e) {}