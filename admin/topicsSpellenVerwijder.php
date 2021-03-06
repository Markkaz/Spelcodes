<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Controleren of er wel een id is */
if((!isset($_GET['spelid'])) || (!isset($_GET['topicid'])))
{
  die('Geen spelid en/of topicid...');
}

/* Permissie controleren */
if(!$cUser -> m_iPermis & 128)
{
  die('Geen permissie...');
}

/* Controleren of het formulier is verzonden */
if(isset($_POST['delete']))
{
  /* Topic verwijderen */
  $sQuery = "DELETE FROM topics WHERE topicid='" . add($_GET['topicid']) . "';";
  if(mysql_query($sQuery))
  {
    /* Reacties verwijderen */
    $sQuery = "DELETE FROM berichten WHERE topicid='" . add($_GET['topicid']) . "';";
    if(mysql_query($sQuery))
    {
      /* Verwijzingen naar spellen verwijderen */
      $sQuery = "DELETE FROM spellenhulp WHERE topicid='" . add($_GET['topicid']) . "';";
      if(mysql_query($sQuery))
      {
        header('Location: topicsSpellen.php?spelid=' . $_GET['spelid']);
      }
      else
      {
        $cTPL -> setPlace('TITEL', 'Fout met database');
        $cTPL -> setPlace('CONTENT', 'Er is iets fout gegaan met de database. Hierdoor is je request niet volledig verwerkt');
      }
    }
    else
    {
      $cTPL -> setPlace('TITEL', 'Fout met database');
      $cTPL -> setPlace('CONTENT', 'Er is iets fout gegaan met de database. Hierdoor is je request niet goed verwerkt');
    }
  }
  else
  {
    $cTPL -> setPlace('TITEL', 'Fout met database');
    $cTPL -> setPlace('CONTENT', 'Er is iets fout gegaan met de database. Hierdoor is je request niet verwerkt');
  }
}
else
{
  $cTPL -> setPlace('TITEL', 'Admin - Spellen - Topic verwijderen');
  $cTPL -> setFile('CONTENT', 'Templates/topicsSpellenVerwijder.tpl');
  $cTPL -> parse();
  
  $cTPL -> setPlace('TOPICID', $_GET['topicid']);
  $cTPL -> setPlace('SPELID', $_GET['spelid']);
}

$cTPL -> show();
?>