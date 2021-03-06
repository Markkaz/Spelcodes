<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Permissie testen */
if((!$cUser -> m_iPermis & 1) && (!$cUser -> m_iPermis & 128))
{
  die('Geen permissie...');
}

$cTPL -> setPlace('TITEL', 'Admin - Spellen beheren');
$cTPL -> setFile('CONTENT', 'Templates/spellen.tpl');
$cTPL -> parse();

/* Berekeningen voor navigatie maken */
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
  $cTPL -> setPlace('VORIGE', '<a href="spellen.php?p=' . ($iP - 1) . '">Vorige</a>');
}
if(($iP + 1) < $iPages)
{
  $cTPL -> setPlace('VOLGENDE', '<a href="spellen.php?p=' . ($iP + 1) . '">Volgende</a>');
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
    $sNummers .= '<a href="spellen.php?p=' . $i . '">' . ($i + 1) . '</a>';
  }
  if(($i +1) < $iPages)
  {
    $sNummers .= ' - ';
  }
}
$cTPL -> setPlace('NAVIGATIE', $sNummers);

/* Spellen ophalen */
$sQuery = "SELECT s.spelid, s.naam, c.naam AS console FROM spellen s, consoles c
           WHERE c.consoleid=s.consoleid ORDER BY naam LIMIT " . ($iP * $iMaxSpellen) . ", " . $iMaxSpellen . ";";
if($cResult = mysql_query($sQuery))
{
  $sBG = '';
  while($aData = mysql_fetch_assoc($cResult))
  {
    /* Kleur effect om en om doen */
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
    
    /* Permissies controleren */
    if($cUser -> m_iPermis & 1)
    {
      $cTPL -> setBlock('TOEVOEGBEGIN', 'toevoeg');
      $cTPL -> setBlock('TOEVOEGEND', 'end');
      
      $cTPL -> setBlock('EDITBEGIN', 'edit');
      $cTPL -> setBlock('EDITEND', 'end');
      
      $cTPL -> setBlock('DELETEBEGIN', 'delete');
      $cTPL -> setBlock('DELETEEND', 'end');
      $cTPL -> parse();
    }
    
    if($cUser -> m_iPermis & 128)
    {
      $cTPL -> setBlock('TOPICSBEGIN', 'topics');
      $cTPL -> setBlock('TOPICSEND', 'end');
      $cTPL -> parse();
    }
    
    $cTPL -> setPlace('ID', $aData['spelid']);
    $cTPL -> setPlace('NAAM', $aData['naam']);
    $cTPL -> setPlace('CONSOLE', $aData['console']);
    
    $cTPL -> setPlace('BG', $sBG);
    $cTPL -> parse();
  }
}
else
{
  die(mysql_error());
}

$cTPL -> show();
?>