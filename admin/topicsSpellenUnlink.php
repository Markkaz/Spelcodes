<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Controleren of de id er is */
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
if(isset($_POST['unlink']))
{
  $sQuery = "DELETE FROM spellenhulp 
             WHERE spelid='" . add($_GET['spelid']) . "' AND topicid='" . add($_GET['topicid']) . "';";
  if(mysql_query($sQuery))
  {
    $sQuery = "SELECT spelid FROM spellenhulp WHERE topicid='" . add($_GET['topicid']) . "';";
    if($cResult = mysql_query($sQuery))
    {
      if(mysql_num_rows($cResult) <= 0)
      {
        $sQuery = "DELETE FROM topics WHERE topicid='" . add($_GET['topicid']) . "';";
        if(mysql_query($sQuery))
        {
          $sQuery = "DELETE FROM berichten WHERE topicid='" . add($_GET['topicid']) . "';";
          if(mysql_query($sQuery))
          {
            header('Location: topicsSpellen.php?spelid=' .$_GET['spelid']);
          }
          else
          {
            $cTPL -> setPlace('TITEL', 'Fout met database');
            $cTPL -> setPlace('CONTENT', 'Er is iets fout gegaan met de database. Hierdoor is er iets fout gegaan met je request.');
          }
        }
        else
        {
          $cTPL -> setPlace('TITEL', 'Fout met database');
          $cTPL -> setPlace('CONTENT', 'Er is iets fout gegaan met de database. Hierdoor is je request maar gedeeltelijk uitgevoerd.');
        }
      }
      else
      {
        header('Location: topicsSpellen.php?spelid=' . $_GET['spelid']);
      }
    }
    else
    {
      $cTPL -> setPlace('TITEL', 'Fout met database');
      $cTPL -> setPlace('CONTENT', 'Er is iets fout gegaan met de database. Hierdoor is je request maar half uitgevoerd.');
    }
  }
  else
  {
    $cTPL -> setPlace('TITEL', 'Fout met database');
    $cTPL -> setPlace('CONTENT', 'Er is iets fout gegaan met de database. Hierdoor is je request niet uitgevoerd.');
  }
}
else
{
  $cTPL -> setPlace('TITEL', 'Admin - Spellen - Topic ontkoppelen');
  $cTPL -> setFile('CONTENT', 'Templates/topicsSpellenUnlink.tpl');
  $cTPL -> parse();
  
  $cTPL -> setPlace('SPELID', $_GET['spelid']);
  $cTPL -> setPlace('TOPICID', $_GET['topicid']);
}

$cTPL -> show();
?>