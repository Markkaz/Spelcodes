<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Controleren of er een id is */
if(!isset($_GET['spelid']))
{
  die('Geen spelid...');
}

/* Permissie controleren */
if(!$cUser -> m_iPermis & 128)
{
  die('Geen permissie...');
}

/* Controleren of het formulier is verzonden */
if(isset($_POST['titel']))
{
  $sQuery = "INSERT INTO topics (topicid, userid, titel, bericht, datum, tijd)
             VALUES ('', '" . $cUser -> m_iUserid . "', '" . add($_POST['titel']) . "',
                     '" . add($_POST['bericht']) . "', NOW(), NOW());";
  if(mysql_query($sQuery))
  {
    $sQuery = "INSERT INTO spellenhulp (spelid, topicid) VALUES ('" . add($_GET['spelid']) . "',
               '" . mysql_insert_id() . "');";
    if(mysql_query($sQuery))
    {
      header('Location: topicsSpellen.php?spelid=' . $_GET['spelid']);
    }
    else
    {
      $cTPL -> setPlace('TITEL', 'Fout met database');
      $cTPL -> setPlace('CONTENT', 'Er is iets fout gegaan met de database. Hierdoor is maar de helft van de request verwerkt');
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
  $cTPL -> setPlace('TITEL', 'Admin - Spellen - Nieuw topic toevoegen');
  $cTPL -> setFile('CONTENT', 'Templates/topicsSpellenToevoeg.tpl');
  $cTPL -> parse();
  
  $cTPL -> setPlace('SPELID', $_GET['spelid']);
}

$cTPL -> show();
?>