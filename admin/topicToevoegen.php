<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Controleren of er een id is */
if((!isset($_GET['spelid'])) || (!isset($_GET['topicid'])))
{
  die('Geen topicid en/of spelid...');
}

/* Permissie controleren */
if(!$cUser -> m_iPermis & 128)
{
  die('Geen permissie...');
}

$sQuery = "INSERT INTO spellenhulp (spelid, topicid)
           VALUES ('" . add($_GET['spelid']) ."', '" . add($_GET['topicid']) . "');";
if(mysql_query($sQuery))
{
  header('Location: topicsSpellen.php?spelid=' . $_GET['spelid']);
}
else
{
  $cTPL -> setPlace('TITEL', 'Fout met database');
  $cTPL -> setPlace('CONTENT', 'Er is iets fout gegaan met de database. Hierdoor is je request niet verwerkt.');
}

$cTPL -> show();
?>