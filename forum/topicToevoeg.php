<?php

use Webdevils\Spelcodes\ExitException;

try {
    /* Header importeren */
    include('Includes/header.php');

    /* Controleren of er een forumid is */
    if (!isset($_GET['forumid'])) {
        header('Http/1.0 404 Not Found');
        throw new ExitException();
    }

    /* Controleren of de user is ingelogd */
    if ((!$cUser->checkSession()) || (!$cUser->checkCookie())) {
        header('Location: ../loginForm.php');
    }

    /* Melding van nieuwe post bij forum plaatsen */
    $sQuery = "UPDATE forum_forums SET last_post='" . $cUser->m_iUserid . "', last_post_time=NOW()
           WHERE forum_id='" . add($_GET['forumid']) . "';";
    if (mysql_query($sQuery)) {
        /* Topic plaatsen */
        $sQuery = "INSERT INTO forum_topics 
               (forum_id, topic_titel, topic_poster, topic_time, topic_replies, topic_status, topic_views, last_post, last_post_time)
             VALUES
               ('" . add($_GET['forumid']) . "', '" . add($_POST['titel']) . "', '" . $cUser->m_iUserid . "',
                NOW(), 1, 1, 0, '" . $cUser->m_iUserid . "', NOW());";
        if (mysql_query($sQuery)) {
            $sQuery = "INSERT INTO forum_posts (topic_id, post_poster, post_time, post_titel, post_text)
               VALUES (" . mysql_insert_id() . ", " . $cUser->m_iUserid . ", NOW(),
                       '" . add($_POST['titel']) . "', '" . add($_POST['reactie']) . "');";
            $iTopicID = mysql_insert_id();
            if (mysql_query($sQuery)) {
                $cUser->addPost();
                header('Location: viewTopic.php?p=0&topicid=' . $iTopicID);
            } else {
                $cTPL->setPlace('TITEL', 'Fout met database');
                $cTPL->setPlace('CONTENT', 'Doordat er iets fout is gegaan met de database, is er alleen maar een topic geplaatst en geen bericht. Onze excuses voor het ongemak.');
            }
        } else {
            $cTPL->setPlace('TITEL', 'Fout met database');
            $cTPL->setPlace('CONTENT', 'Doordat er iets fout is gegaan met de database, is je request niet verwerkt. Onze excuses hiervoor.');
            print $sQuery . '<br>';
            print mysql_error();
            throw new ExitException();
        }
    } else {
        $cTPL->setPlace('TITEL', 'Fout met database');
        $cTPL->setPlace('CONTENT', 'Er is iets fout gegaan met de database. Hierdoor is je request niet verwerkt. Onze excuses hiervoor.');
    }

    $cTPL->show();
} catch (ExitException $e) {}
