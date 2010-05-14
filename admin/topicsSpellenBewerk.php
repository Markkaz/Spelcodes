<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Controleren of een id is */
if((!isset($_GET['topicid'])) || (!isset($_GET['spelid'])))
{
  die('Geen topicid en/of spelid...');
}

/* Permissie controleren */
if(!$cUser -> m_iPermis & 128)
{
  die('Geen permissie...');
}

/* Controleren of het formulier is verzonden */
if(isset($_POST['titel']))
{
  $sQuery = "UPDATE topics SET titel='" . add($_POST['titel']) . "', bericht='" . add($_POST['bericht']) . "'
             WHERE topicid='" . add($_GET['topicid']) . "';";
  if(mysql_query($sQuery))
  {
    header('Location: topicsSpellen.php?spelid=' . $_GET['spelid']);
  }
  else
  {
    $cTPL -> setPlace('TITEL', 'Fout met database');
    $cTPL -> setPlace('CONTENT', 'Er is iets fout gegaan met de database. Hierdoor is je request niet verwerkt.');
  }
}
else
{
  $cTPL -> setPlace('TITEL', 'Admin - Spellen - Topic bewerken');
  $cTPL -> setFile('CONTENT', 'Templates/topicsSpellenBewerk.tpl');
  $cTPL -> parse();
  
  /* Data ophalen uit database en verwerken */
  $sQuery = "SELECT titel, bericht FROM topics WHERE topicid='" . add($_GET['topicid']) . "';";
  if($cResult = mysql_query($sQuery))
  {
    $aData = mysql_fetch_assoc($cResult);
    $cTPL -> setPlace('TITEL', strip($aData['titel']));
    $cTPL -> setPlace('BERICHT', strip($aData['bericht']));
  }
  
  $cTPL -> setPlace('TOPICID', $_GET['topicid']);
  $cTPL -> setPlace('SPELID', $_GET['spelid']);
}

$cTPL -> show();
?>