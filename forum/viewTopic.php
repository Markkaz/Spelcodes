<?php
/* Includes importeren */
include('Includes/header.php');
include_once('Includes/smiley.php');
include_once(__DIR__.'/../Includes/bbcode.php');

/* Navigatie class importeren */
include_once('Classes/navigatie.php');

/* Controleren of er een forumid en een p is */
if ((isset($_GET['topicid'])) && (isset($_GET['p']))) {


    $cTPL->setPlace('TITEL', 'Forum - Berichten');
    $cTPL->setFile('CONTENT', __DIR__ . '/Templates/viewTopic.tpl');
    $cTPL->parse();

    /* Data over topic ophalen */
    $sQuery = "SELECT forum_id, topic_titel FROM forum_topics WHERE topic_id='" . add($_GET['topicid']) . "';";
    if ($cResultTopic = mysql_query($sQuery)) {
        $aDataTopic = mysql_fetch_assoc($cResultTopic);
        $cTPL->setPlace('FORUMID', $aDataTopic['forum_id']);
        $cTPL->setPlace('TOPIC_TITEL', $aDataTopic['topic_titel']);
        $cTPL->setPlace('TOPICID', $_GET['topicid']);

        /* Menu navigatie */
        $cNav = new Navigatie(add($_GET['topicid']));
        $cTPL->setPlace('NUMMERS', $cNav->makeNavigation(add($_GET['p'])));

        if ($_GET['p'] > 0) {
            $cTPL->setPlace('PREVIOUS', '<a href="viewTopic.php?topicid=' . $_GET['topicid'] . '&p=' . ($_GET['p'] - 1) . '">Vorige</a>');
        }
        if ($_GET['p'] < ($cNav->m_iPages - 1)) {
            $cTPL->setPlace('NEXT', '<a href="viewTopic.php?topicid=' . $_GET['topicid'] . '&p=' . ($_GET['p'] + 1) . '">Volgende</a>');
        }

        /* Berichten ophalen */
        $sQuery = "SELECT p.post_id, u.userid, u.username, p.post_time, p.post_titel, p.post_text FROM forum_posts p, users u
             WHERE p.post_poster=u.userid AND topic_id='" . add($_GET['topicid']) . "'
             LIMIT " . (add($_GET['p']) * $cNav->m_iMax_posts) . ", " . $cNav->m_iMax_posts . ";";
        if ($cResult = mysql_query($sQuery)) {
            while ($aData = mysql_fetch_assoc($cResult)) {
                $cTPL->setBlock('REACTIES', 'reacties');
                $cTPL->parse();

                $cTPL->setPlace('TITEL', $aData['post_titel']);
                $cTPL->setPlace('USERNAME', $aData['username']);
                $cTPL->setPlace('MOMENT', $aData['post_time']);
                $cTPL->setPlace('BERICHT', bbcode(smiley(strip_tags(strip($aData['post_text'])))));

                if (($cUser->m_iUserid == $aData['userid']) || ($cUser->m_iPermis & 2)) {
                    $cTPL->setBlock('EDIT', 'edit');
                    $cTPL->parse();

                    $cTPL->setPlace('POSTID', $aData['post_id']);
                } else {
                    $cTPL->setPlace('EDIT', '');
                }

                $sReactieTitel = $aData['post_titel'];
                $cTPL->parse();
            }
            $cTPL->setPlace('TITEL_REACTIE', 'Re: ' . add($sReactieTitel));
        }
    }

    $cTPL->show();
} else {
    header('Http/1.0 404 Page Not Found');
}
