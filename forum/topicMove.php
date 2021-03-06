<?php
/* Header importeren */
include('Includes/header.php');

/* Controleren of er een topicid is */
if((!isset($_GET['topicid'])) || (!isset($_GET['forumid'])))
{
  die('Geen topicid en/of forumid...');
}

/* Controleren of je bent ingelogd */
if((!$cUser -> checkSession()) && (!$cUser -> checkCookie()))
{
  header('Location: ../loginForm.php');
}

/* Permissie controleren */
if(!$cUser -> m_iPermis & 2)
{
  die('Geen permissie...');
}

/* Controleren of het formulier is verzonden */
if(isset($_POST['forumid']))
{
  $sQuery = "UPDATE forum_topics SET forum_id='" . add($_POST['forumid']) . "'
             WHERE topic_id='" . add($_GET['topicid']) . "';";
  if(mysql_query($sQuery))
  {
    header('Location: viewForum.php?forumid=' . $_POST['forumid']);
  }
  else
  {
    $cTPL -> setPlace('TITEL', 'Fout met database');
    $cTPL -> setPlace('CONTENT', 'Door een fout met de database, is je request niet verwerkt.');
  }
}
else
{
  $cTPL -> setPlace('TITEL', 'Forum - Verplaats topic');
  $cTPL -> setFile('CONTENT', 'Templates/topicMove.tpl');
  $cTPL -> parse();
  
  /* Fora ophalen */
  $sQuery = "SELECT forum_id, forum_titel FROM forum_forums ORDER BY forum_titel;";
  if($cResult = mysql_query($sQuery))
  {
    while($aData = mysql_fetch_assoc($cResult))
    {
      $cTPL -> setBlock('OPTION', 'option');
      $cTPL -> parse();
      
      $cTPL -> setPlace('OPTIONFORUMID', $aData['forum_id']);
      $cTPL -> setPlace('FORUMNAAM', $aData['forum_titel']);
      
      if($aData['forum_id'] == $_GET['forumid'])
      {
        $cTPL -> setPlace('SELECTED', ' "selected"');
      }
      $cTPL -> parse();
    }
  }
  $cTPL -> setPlace('FORUMID', $_GET['forumid']);
  $cTPL -> setPlace('TOPICID', $_GET['topicid']);
}

$cTPL -> show();
?>