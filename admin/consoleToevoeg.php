<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Permissie controleren */
if(!$cUser -> m_iPermis & 16)
{
  die('Geen permissie...');
}

/* Controleren of het formulier is verzonden */
if(isset($_POST['naam']))
{
  $sQuery = "INSERT INTO consoles (consoleid, naam) VALUES ('', '" . add($_POST['naam']) . "');";
  if(mysql_query($sQuery))
  {
    header('Location: consoles.php');
  }
  else
  {
    $cTPL -> setPlace('TITEL', 'Fout met database');
    $cTPL -> setPlace('CONTENT', 'Er is iets fout gegaan met de database. Hierdoor kon je request niet worden verwerkt.');
  }
}
else
{
  $cTPL -> setPlace('TITEL', 'Admin - Console toevoegen');
  $cTPL -> setFile('CONTENT', 'Templates/consoleToevoeg.tpl');
}

$cTPL -> show();
?>