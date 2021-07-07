<?php
error_reporting(E_ALL & ~E_DEPRECATED);

session_start();

/* Classes importeren */
include_once('Classes/User.php');
include_once('Classes/Template.php');

/* Includes importeren */
include_once('Includes/connect.php');
include_once('Includes/slashes.php');

/* Classes initialiseren */
$cUser = new User();

/* Verbinden met mysql */
connectDB();

try {
    if(!isset($_GET['id'])) {
        throw new Exception('Game id parameter missing');
    }

    if(!isset($_GET['topicid'])) {
        throw new Exception('Topic id parameter missing');
    }

    if($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['reactie'])) {
        throw new Exception('Form isn\'t posted');
    }

    $sql = 'SELECT EXISTS(
                SELECT * FROM topics t 
                    JOIN spellenhulp sh ON sh.topicid = t.topicid 
                    WHERE t.topicid = '.add($_GET['topicid']).' 
                        AND sh.spelid = '.add($_GET['id']).'
            ) as topic_exists';
    $result = mysql_query($sql);
    if(!$result) {
        throw new Exception('Error finding the topic from the database');
    }

    $data = mysql_fetch_assoc($result);
    if(!$data || !$data['topic_exists']) {
        throw new Exception('Topic doesn\'t exist');
    }

    if (($cUser->checkSession()) || ($cUser->checkCookie())) {
        $sQuery = "INSERT INTO berichten (topicid, userid, bericht, datum, tijd)
           VALUES ('" . add($_GET['topicid']) . "', '" . $cUser->m_iUserid . "',
           '" . add($_POST['reactie']) . "', NOW(), NOW());";
        if (mysql_query($sQuery)) {
            $cUser->addPost();
            header('Location: gameview.php?id=' . $_GET['id'] . '&topicid=' . $_GET['topicid']);
        } else {
            throw new Exception('Error adding game comment');
        }
    } else {
        header('Location: loginForm.php');
    }
} catch (Exception $e) {
    header('HTTP/1.0 404 Page not Found');
}