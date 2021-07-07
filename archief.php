<?php
error_reporting(E_ALL & ~E_DEPRECATED);

session_start();

/* Classes importeren */
include_once('Classes/User.php');
include_once('Classes/Template.php');

/* Includes importeren */
include_once('Includes/connect.php');
include_once('Includes/slashes.php');

/* Classes initialiseren */
$cUser = new User();
$cTPL = new Template('Templates/main.tpl');

/* Verbinding met de database maken */
connectDB();

/* Controleren of je bent ingelogd */
include('Includes/login.php');

/* Data op pagina zetten */

$cTPL -> setPlace('TITEL', 'Nieuws archief');
$cTPL -> setFile('CONTENT', 'Templates/archief.tpl');
$cTPL -> parse();

$sQuery = "SELECT nieuwsid, titel, datum, tijd FROM nieuws ORDER BY datum DESC, titel DESC;";
$sBG = '';
if($cResult = mysql_query($sQuery))
{
  while($aData = mysql_fetch_assoc($cResult))
  {
    if($sBG == '')
    {
      $sBG = 'img/patroon.gif';
    }
    else
    {
      $sBG = '';
    }
    $cTPL -> setBlock('NIEUWS', 'nieuws');
    $cTPL -> parse();

    $cTPL -> setPlace('BG', $sBG);
    $cTPL -> setPlace('ID', add($aData['nieuwsid']));
    $cTPL -> setPlace('TITEL', add($aData['titel']));
    $cTPL -> setPlace('DATUM', add($aData['datum'] . ' ' . $aData['tijd']));
    $cTPL -> parse();
  }
}

$cTPL -> show();