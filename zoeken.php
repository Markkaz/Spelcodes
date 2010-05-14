<?php
error_reporting(E_ALL);

session_start();

/* Controleren of er gezocht is */
if(!isset($_GET['zoek']))
{
  die('Geen zoek...');
}

/* Includes importeren */
include('Includes/connect.php');
include('Includes/slashes.php');

/* Classes importeren */
include('Classes/User.php');
include('Classes/Template.php');

/* Classes initialiseren */
$cUser = new User();
$cTPL = new Template('Templates/main.tpl');

/* Verbinding maken met db */
connectDB();

include('Includes/login.php');

$cTPL -> setPlace('TITEL', 'Zoeken');
$cTPL -> setFile('CONTENT', 'Templates/zoeken.tpl');
$cTPL -> parse();

$cTPL -> setPlace('ZOEKEN', htmlentities($_GET['zoek']));

/* Spellen ophalen */
$sQuery = "SELECT s.spelid, s.naam, c.naam AS console, s.rating, s.stemmen FROM spellen s, consoles c
           WHERE s.consoleid=c.consoleid AND s.naam LIKE '%" . add($_GET['zoek']) . "%' ORDER BY s.naam;";
if($cResult = mysql_query($sQuery))
{  
  if(mysql_num_rows($cResult) <= 0)
  {
    // Zoekwoord opslaan
    $sQuery = "INSERT INTO zoekwoorden (zoekwoord) VALUES ('" . $_GET['zoek'] . "');";
    mysql_query($sQuery);
    $cTPL -> setBlock('RESULTATEN', 'niks');
  }
  else
  {
    $cTPL -> setBlock('RESULTATEN', 'resultaten');
    $cTPL -> parse();
    
    $sBG = '';
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
      $cTPL -> setBlock('SPEL', 'spel');
      $cTPL -> parse();
      
      $cTPL -> setPlace('SPELID', $aData['spelid']);
      $cTPL -> setPlace('NAAM', preg_replace('#' . $_GET['zoek'] . '#i', '<b>' . $_GET['zoek'] .'</b>', $aData['naam']));
      $cTPL -> setPlace('CONSOLE', $aData['console']);
      $cTPL -> setPlace('BG', $sBG);
      
      /* Sterren berekenen */
      if($aData['stemmen'] <= 0)
      {
        $iSterren = 0;
      }
      else
      {
        $iSterren = round(($aData['rating'] / $aData['stemmen']) * 2) / 2;
      }
      
      /* Sterren weergeven */
      for($i = 1; $i <= 5; $i++)
      {
        $iSterren -= 1;
        if($iSterren >= 0)
        {
          $cTPL -> setBlock('STER' . $i, 'helester');
        }
        elseif($iSterren == -0.5)
        {
          $cTPL -> setBlock('STER' . $i, 'halvester');
        }
        else
        {
          $cTPL -> setBlock('STER' . $i, 'legester');
        }
      }
      $cTPL -> parse();
    }
  }
}
else
{
  die(mysql_error());
}

$cTPL -> show();
?>