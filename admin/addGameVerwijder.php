<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Controleren op id */
if((!isset($_GET['id'])) || (!isset($_GET['spelid'])))
{
  die('Geen id en/of spelid...');
}

/* Permissie controleren */
if(!$cUser -> m_iPermis & 256)
{
  die('Geen permissie...');
}

/* Controleren of het formulier is verzonden */
if(isset($_POST['delete']))
{
  $sQuery = "DELETE FROM spellenview 
             WHERE spelid='" . add($_GET['spelid']) . "' AND consoleid='" . add($_GET['id']) . "';";
  if(mysql_query($sQuery))
  {
    header('Location: addGame.php?id=' . $_GET['id']);
  }
  else
  {
    $cTPL -> setPlace('TITEL', 'Fout met database');
    $cTPL -> setPlace('CONTENT', 'Door een fout in de database is je request niet verwerkt.');
  }
}
else
{
  $cTPL -> setPlace('TITEL', 'Admin - Consoles - Favoriet verwijderen');
  $cTPL -> setFile('CONTENT', 'Templates/addGameVerwijder.tpl');
  $cTPL -> parse();
  
  $cTPL -> setPlace('ID', $_GET['id']);
  $cTPL -> setPlace('SPELID', $_GET['spelid']);
}

$cTPL -> show();
?>