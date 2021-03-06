<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Permissie controleren */
if(!$cUser -> m_iPermis & 32)
{
  die('Geen permissie...');
}

$cTPL -> setPlace('TITEL', 'Admin - Nieuws beheren');
$cTPL -> setFile('CONTENT', 'Templates/nieuws.tpl');
$cTPL -> parse();

/* Waardes ophalen en verwerken */
$sQuery = "SELECT n.nieuwsid, n.titel, u.username, n.datum, n.tijd FROM nieuws n, users u
           WHERE n.userid=u.userid ORDER BY n.datum DESC, tijd DESC";
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
    $cTPL -> setBlock('NIEUWS', 'nieuws');
    $cTPL -> parse();
    
    $cTPL -> setPlace('ID', $aData['nieuwsid']);
    $cTPL -> setPlace('TITEL', $aData['titel']);
    $cTPL -> setPlace('AUTEUR', $aData['username']);
    $cTPL -> setPlace('DATUM', $aData['datum']);
    $cTPL -> setPlace('TIJD', $aData['tijd']);
    $cTPL -> setPlace('BG', $sBG);
  }
}

$cTPL -> show();
?>