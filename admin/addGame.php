<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Controleren op id */
if(!isset($_GET['id']))
{
  die('Geen id...');
}

if(!$cUser -> m_iPermis & 256)
{
  die('Geen permissie...');
}

$cTPL -> setPlace('TITEL', 'Admin - Consoles - Favoriete spellen');
$cTPL -> setFile('CONTENT', 'Templates/addGame.tpl');
$cTPL -> parse();

$sQuery = "SELECT s.spelid, s.naam FROM spellen s, spellenview sv 
           WHERE sv.spelid=s.spelid AND s.consoleid='" . add($_GET['id']) . "';";
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