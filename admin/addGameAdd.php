<?php
error_reporting(E_ALL);

/* Header file importeren */
include('Includes/header.php');

/* Controleren of de id's er zijn */
if((!isset($_GET['id'])) || (!isset($_GET['spelid'])))
{
  die('Geen id en/of spelid...');
}

/* Permissie controleren */
if(!$cUser -> m_iPermis & 256)
{
  die('Geen permissie...');
}

$sQuery = "INSERT INTO spellenview (consoleid, spelid)
           VALUES ('" . add($_GET['id']) . "', '" . add($_GET['spelid']) . "');";
if(mysql_query($sQuery))
{
  header('Location: addGame.php?id=' . $_GET['id']);
}
else
{
  $cTPL -> setPlace('TITEL', 'Fout met database');
  $cTPL -> setPlace('CONTENT', 'Door een fout met de database is je request niet verwerkt.');
}

$cTPL -> show();
?>