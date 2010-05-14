<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Controleren of er een id is */
if(!isset($_GET['id']))
{
  die('Geen id...');
}

/* Permissie controleren */
if(!$cUser -> m_iPermis & 32)
{
  die('Geen permissie...');
}

/* Controleren of het formulier is verzonden */
if(isset($_POST['delete']))
{
  $sQuery = "DELETE FROM nieuwsreacties WHERE nieuwsid='" . add($_GET['id']) . "';";
  if(mysql_query($sQuery))
  {
    $sQuery = "DELETE FROM nieuws WHERE nieuwsid='" . add($_GET['id']) . "';";
    if(mysql_query($sQuery))
    {
      header('Location: nieuws.php');
    }
    else
    {
      $cTPL -> setPlace('TITEL', 'Fout met database');
      $cTPL -> setPlace('CONTENT', 'Doordat er iets fout ging met de database, zijn alleen de reacties verwijderd.');
    }
  }
  else
  {
    $cTPL -> setPlace('TITEL', 'Fout met database');
    $cTPL -> setPlace('CONTENT', 'Doordat er iets fout ging met de database is je request niet verwerkt.');
  }
}
else
{
  $cTPL -> setPlace('TITEL', 'Admin - Nieuwsbericht verwijderen');
  $cTPL -> setFile('CONTENT', 'Templates/nieuwsVerwijder.tpl');
  $cTPL -> parse();
  
  $cTPL -> setPlace('ID', $_GET['id']);
}

$cTPL -> show();
?>