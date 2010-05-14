<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Controleren op id */
if(!isset($_GET['id']))
{
  die('Geen id...');
}

/* Permissie controleren */
if(!$cUser -> m_iPermis & 256)
{
  die('Geen permissie...');
}

$cTPL -> setPlace('TITEL', 'Admin - Consoles - Favoriet toevoegen');
$cTPL -> setFile('CONTENT', 'Templates/addGameToevoeg.tpl');
$cTPL -> parse();

/* Data voor pagina ophalen en verwijderen */
$sQuery = "SELECT spelid, naam FROM spellen WHERE consoleid='" . add($_GET['id']) . "' ORDER BY naam;";
if($cResult = mysql_query($sQuery))
{
  $sBG = '';
  while($aData = mysql_fetch_assoc($cResult))
  {
    if($sBG == '')
    {
      $sBG = '../img/patroon.gif';
    }
    else
    {
      $sBG = '';
    }
    $cTPL -> setBlock('SPEL', 'spel');
    $cTPL -> parse();
    
    $cTPL -> setPlace('SPELID', $aData['spelid']);
    $cTPL -> setPlace('NAAM', $aData['naam']);
    $cTPL -> setPlace('BG', $sBG);
    $cTPL -> parse();
  }
}

$cTPL -> setPlace('ID', $_GET['id']);

$cTPL -> show();
?>