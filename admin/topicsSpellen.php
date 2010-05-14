<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Controleren of er een id is */
if(!isset($_GET['spelid']))
{
  die('Geen spelid...');
}

/* Permissie controleren */
if(!$cUser -> m_iPermis & 128)
{
  die('Geen permissie...');
}

$cTPL -> setPlace('TITEL', 'Admin - Spellen - Overzicht topics');
$cTPL -> setFile('CONTENT', 'Templates/topicsSpellen.tpl');
$cTPL -> parse();

/* Spel data ophalen en weergeven */
$sQuery = "SELECT s.naam, c.naam AS console FROM spellen s, consoles c
           WHERE s.consoleid=c.consoleid AND spelid='" . add($_GET['spelid']) . "';";
if($cResult = mysql_query($sQuery))
{
  $aData = mysql_fetch_assoc($cResult);
  $cTPL -> setPlace('SPEL', $aData['naam']);
  $cTPL -> setPlace('CONSOLE', $aData['console']);
}

/* Topics ophalen en weergeven */
$sQuery = "SELECT t.topicid, t.titel, u.username, t.datum, t.tijd FROM spellenhulp sh, topics t, users u
           WHERE sh.topicid=t.topicid AND u.userid=t.userid AND sh.spelid='" . add($_GET['spelid']) . "';";
if($cResult = mysql_query($sQuery))
{
  $sBG = '';
  while($aData = mysql_fetch_assoc($cResult))
  {
    if($sBG == '') {
      $sBG = '../img/patroon.gif';
    }
    else
    {
      $sBG = '';
    }
    $cTPL -> setBlock('TOPIC', 'topic');
    $cTPL -> parse();
    
    $cTPL -> setPlace('TOPICID', $aData['topicid']);
    $cTPL -> setPlace('TITEL', $aData['titel']);
    $cTPL -> setPlace('AUTEUR', $aData['username']);
    $cTPL -> setPlace('DATUM', $aData['datum']);
    $cTPL -> setPlace('TIJD', $aData['tijd']);
    $cTPL -> setPlace('BG', $sBG);
    $cTPL -> parse();
  }
}
$cTPL -> setPlace('SPELID', $_GET['spelid']);

$cTPL -> show();
?>