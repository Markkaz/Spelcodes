<?php
/* Header importeren */
include('Includes/header.php');

/* Controleren of er een forumid is */
if(!isset($_GET['forumid']))
{
  die('Geen forumid...');
}

/* Controleren of je bent ingelogd */
if((!$cUser -> checkSession()) && (!$cUser -> checkCookie()))
{
  header('Location: ../../loginForm.php');
}

/* Permissie controleren */
if(!$cUser -> m_iPermis & 4)
{
  die('Geen permissie...');
}

/* Controleren of het formulier is verzonden */
if(isset($_POST['delete']))
{
  /* Controleren of er geen topics nog staan in het forum */
  $sQuery = "SELECT count(topic_id) FROM forum_topics WHERE forum_id='" . add($_GET['forumid']) . "';";
  if($cResult = mysql_query($sQuery))
  {
    if(mysql_result($cResult, 0) > 0)
    {
      $cTPL -> setPlace('TITEL', 'Forum - Admin - Kon forum niet verwijderen');
      $cTPL -> setPlace('CONTENT', 'Er zitten nog topics in het forum, daarom kan het niet worden verwijderd.');
    }
    else
    {
      $sQuery = "DELETE FROM forum_forums WHERE forum_id='" . add($_GET['forumid']) . "';";
      if(mysql_query($sQuery))
      {
        header('Location: index.php');
      }
      else
      {
        $cTPL -> setPlace('TITEL', 'Fout met database');
        $cTPL -> setPlace('CONTENT', 'Door een fout met de database, kon het het forum niet worden verwijderd.');
      }
    }
  }
  else
  {
    $cTPL -> setPlace('TITEL', 'Fout met database');
    $cTPL -> setPlace('CONTENT', 'Door een fout met de database, kon het forum niet worden verwijderd.');
  }
}
else
{
  $cTPL -> setPlace('TITEL', 'Forum - Admin - Forum verwijderen');
  $cTPL -> setFile('CONTENT', 'Templates/forumVerwijder.tpl');
  $cTPL -> parse();
  
  $cTPL -> setPlace('FORUMID', $_GET['forumid']);
}

$cTPL -> show();
?>