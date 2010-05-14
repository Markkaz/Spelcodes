<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Controleren of er een id is */
if(!isset($_GET['spelid']))
{
  die('Geen id...');
}

/* Permissie controleren */
if(!$cUser -> m_iPermis & 128)
{
  die('Geen permissie...');
}

$cTPL -> setPlace('TITEL', 'Admin - Spellen - Oud topic toevoegen');
$cTPL -> setFile('CONTENT', 'Templates/topicsSpellenOud.tpl');
$cTPL -> parse();

/* Data over spel ophalen */
$sQuery = "SELECT s.naam, c.naam AS console FROM spellen s, consoles c
           WHERE c.consoleid=s.consoleid AND s.spelid='" . add($_GET['spelid']) . "';";
if($cResult = mysql_query($sQuery))
{
  $aData = mysql_fetch_assoc($cResult);
  $cTPL -> setPlace('TOEVOEG', $aData['naam']);
  $cTPL -> setPlace('CONSOLE', $aData['console']);
}
$cTPL -> parse();

/* Navigatie berekenen */
$iMaxSpellen = 50;

if(isset($_GET['p']))
{
  $iP = $_GET['p'];
}
else
{
  $iP = 0;
}

$sQuery = "SELECT count(spelid) FROM spellen;";
if($cResult = mysql_query($sQuery))
{
  $iSpellen = mysql_result($cResult, 0);
  $iPages = ceil($iSpellen / $iMaxSpellen);
}

/* Navigatie weergeven */
if($iP > 0)
{
  $cTPL -> setPlace('VORIGE', '<a href="topicsSpellenOud.php?spelid=' . $_GET['spelid'] . '&p=' . ($iP - 1) . '">Vorige</a>');
}
if(($iP + 1) < $iPages)
{
  $cTPL -> setPlace('VOLGENDE', '<a href="topicsSpellenOud.php?spelid=' . $_GET['spelid'] . '&p=' . ($iP + 1) . '">Volgende</a>');
}

$sNummers = '';
for($i = 0; $i < $iPages; $i++)
{
  if($i == $iP)
  {
    $sNummers .= '<b>[' . ($i + 1) . ']</b>';
  }
  else
  {
    $sNummers .= '<a href="topicsSpellenOud.php?spelid=' . $_GET['spelid'] . '&p=' . $i . '">' . ($i + 1) . '</a>';
  }
  if(($i + 1) < $iPages)
  {
    $sNummers .= ' - ';
  }
}
$cTPL -> setPlace('NAVIGATIE', $sNummers);

/* Data spellen ophalen en verwerken */
$sQuery = "SELECT s.spelid, s.naam, c.naam AS console FROM spellen s, consoles c
           WHERE c.consoleid=s.consoleid ORDER BY s.naam LIMIT " . ($iP * $iMaxSpellen) . ", " . $iMaxSpellen . ";";
if($cResultSpel = mysql_query($sQuery))
{
  while($aDataSpel = mysql_fetch_assoc($cResultSpel))
  {
    $cTPL -> setBlock('SPEL', 'spel');
    $cTPL -> parse();
    
    $cTPL -> setPlace('NAAMSPEL', $aDataSpel['naam']);
    $cTPL -> setPlace('CONSOLESPEL', $aDataSpel['console']);
    $cTPL -> parse();
    
    /* Data topics bij spellen ophalen en verwerken */
    $sQuery = "SELECT t.topicid, t.titel, u.username, t.datum, t.tijd FROM topics t, users u, spellenhulp sh
               WHERE t.userid=u.userid AND t.topicid=sh.topicid AND sh.spelid='" . $aDataSpel['spelid'] . "';";
    if($cResultTopic = mysql_query($sQuery))
    {
      $bSkip = false;
      while($aDataTopic = mysql_fetch_assoc($cResultTopic))
      {
        $bSkip = true;
        $cTPL -> setBlock('TOPIC', 'topic');
        $cTPL -> parse();
        
        $cTPL -> setPlace('ID', $aDataTopic['topicid']);
        $cTPL -> setPlace('TITEL', $aDataTopic['titel']);
        $cTPL -> setPlace('AUTEUR', $aDataTopic['username']);
        $cTPL -> setPlace('DATUM', $aDataTopic['datum']);
        $cTPL -> setPlace('TIJD', $aDataTopic['tijd']);
        $cTPL -> setPlace('SPELID', $_GET['spelid']);
        $cTPL -> setPlace('IDSPEL', $aDataSpel['spelid']);
        $cTPL -> parse();
      }
      if(!$bSkip)
      {
        $cTPL -> setBlock('LEEG', 'topic');
      }
      $cTPL -> setPlace('TOPIC', '');
    }
  }
}
else
{
  die(mysql_error());
}

$cTPL -> show();
?>