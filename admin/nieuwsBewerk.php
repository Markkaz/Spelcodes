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
if(isset($_POST['titel']))
{
  $sQuery = "UPDATE nieuws SET titel='" . add($_POST['titel']) . "', bericht='" . add($_POST['bericht']) . "'
             WHERE nieuwsid='" . add($_GET['id']) . "';";
  if(mysql_query($sQuery))
  {
    header('Location: nieuws.php');
  }
  else
  {
    $cTPL -> setPlace('TITEL', 'Admin - Fout met database');
    $cTPL -> setPlace('CONTENT', 'Doordat er iets fout ging met de database, is je request niet verwerkt.');
  }
}
else
{
  $cTPL -> setPlace('TITEL', 'Admin - Nieuwsbericht bewerken');
  $cTPL -> setFile('CONTENT', 'Templates/nieuwsBewerk.tpl');
  $cTPL -> parse();
  
  /* Data ophalen en verwerken */
  $sQuery = "SELECT titel, bericht FROM nieuws WHERE nieuwsid='" . add($_GET['id']) . "';";
  if($cResult = mysql_query($sQuery))
  {
    $aData = mysql_fetch_assoc($cResult);
    $cTPL -> setPlace('TITEL', $aData['titel']);
    $cTPL -> setPlace('BERICHT', $aData['bericht']);
  }
  $cTPL -> setPlace('ID', $_GET['id']);
}

$cTPL -> show();
?>