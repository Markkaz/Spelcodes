<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Permissie controleren */
if((!$cUser -> m_iPermis & 16) && (!$cUser -> m_iPermis & 256))
{
  die('Geen permissie...');
}

$cTPL -> setPlace('TITEL', 'Admin - Consoles beheren');
$cTPL -> setFile('CONTENT', 'Templates/consoles.tpl');
$cTPL -> parse();

/* Gegevens ophalen en verwerken */
$sQuery = "SELECT consoleid, naam FROM consoles ORDER BY consoleid";
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
    $cTPL -> setBlock('CONSOLE', 'console');
    $cTPL -> parse();
    
    /* Permissies controleren */
    if($cUser -> m_iPermis & 16)
    {
      $cTPL -> setBlock('CONSOLESTART', 'consolestart');
      $cTPL -> setBlock('CONSOLEEND', 'end');
      $cTPL -> setBlock('EDITSTART', 'editstart');
      $cTPL -> setBlock('EDITEND', 'end');
      $cTPL -> setBlock('DELETESTART', 'deletestart');
      $cTPL -> setBlock('DELETEEND', 'end');
    }
    if($cUser -> m_iPermis & 256)
    {
      $cTPL -> setBlock('ADDGAMESTART', 'addgamestart');
      $cTPL -> setBlock('ADDGAMEEND', 'end');
    }
    $cTPL -> parse();
    
    $cTPL -> setPlace('ID', $aData['consoleid']);
    $cTPL -> setPlace('NAAM', $aData['naam']);
    $cTPL -> setPlace('BG', $sBG);
    $cTPL -> parse();
  }
}

$cTPL -> show();
?>