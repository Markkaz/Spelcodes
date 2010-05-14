<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Permissie controleren */
if(!$cUser -> m_iPermis & 64)
{
  die('Geen permissie...');
}

$cTPL -> setPlace('TITEL', 'Admin - Links beheren');
$cTPL -> setFile('CONTENT', 'Templates/links.tpl');
$cTPL -> parse();

/* Data ophalen en verwerken */
$sQuery = "SELECT linkid, link, url, incomming, outcomming FROM links ORDER BY link;";
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
    $cTPL -> setBlock('LINK', 'link');
    $cTPL -> parse();
    
    $cTPL -> setPlace('ID', $aData['linkid']);
    $cTPL -> setPlace('LINKNAAM', $aData['link']);
    $cTPL -> setPlace('URL', $aData['url']);
    $cTPL -> setPlace('IN', $aData['incomming']);
    $cTPL -> setPlace('OUT', $aData['outcomming']);
    $cTPL -> setPlace('BG', $sBG);
  }
}

$cTPL -> show();
?>