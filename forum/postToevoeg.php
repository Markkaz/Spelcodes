<?php
/* Header importeren */
include('Includes/header.php');

/* Controleren of je bent ingelogd */
if((!$cUser -> checkSession()) && (!$cUser -> checkCookie()))
{
  header('Location: ../loginForm.php');
  exit;
}

/* Controleren of er een topicid is */
if(!isset($_GET['topicid']))
{
  die('Geen topicid...');
}

/* Haal forum id op */
$sQuery = "SELECT forum_id FROM forum_topics WHERE topic_id='" . add($_GET['topicid']) . "';";
if($cResult = mysql_query($sQuery))
{
  $iForumID = mysql_result($cResult, 0);
  
  /* Melding van nieuwe post in forum geven */
  $sQuery = "UPDATE forum_forums SET last_post='" . $cUser -> m_iUserid . "', last_post_time=NOW() 
             WHERE forum_id='" . $iForumID . "';";
  mysql_query($sQuery);
  
  /* Melding van nieuwe post in topic geven */
  $sQuery = "UPDATE forum_topics SET last_post='" . $cUser -> m_iUserid . "', last_post_time=NOW()
             WHERE topic_id='" . add($_GET['topicid']) . "';";
  mysql_query($sQuery);
  
}

/* Verwerk de request */
$sQuery = "INSERT INTO forum_posts (post_id, topic_id, post_poster, post_time, post_titel, post_text)
           VALUES ('', '" . add($_GET['topicid']) . "', '" . $cUser -> m_iUserid . "', NOW(), 
                   '" . add($_POST['titel']) . "', '" . add($_POST['reactie']) . "');";
if($cResult = mysql_query($sQuery))
{
  $cUser -> addPost();
  header('Location: viewTopic.php?p=0&topicid=' . $_GET['topicid']);
}
else
{
  $cTPL -> setPlace('TITEL', 'Fout met de database');
  $cTPL -> setPlace('CONTENT', 'Er ging iets fout met de database. Hierdoor kon je request niet verwerkt worden. Onze excuses hiervoor');
}

$cTPL -> show();
?>