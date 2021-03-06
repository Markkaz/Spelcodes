<?php
error_reporting(E_ALL);

/* Classes importeren */
include('../Classes/Template.php');

/* Includes importeren */
include('../Includes/slashes.php');

/* Classes initialiseren */
$cTPL = new Template('../Templates/cheats.tpl');

if(isset($_POST['cheat0']))
{
  $sUitwerking = "<table>\n<tr>\n<th>Cheat: </th>\n<th>Uitwerking: </th>\n</tr>\n";
  
  $sRegel = "<td background=\"img/patroon.gif\">";
  
  $bFlag = true;
  
  for($iTeller = 0; $iTeller < $_GET['aantal']; $iTeller++)
  {
    if($bFlag)
    {
      $sUitwerking .= "<tr>\n" . $sRegel . strip($_POST['cheat' . $iTeller]) . "</td>\n";
      $sUitwerking .= $sRegel . strip($_POST['uitwerking' . $iTeller]) . "</td>\n</tr>\n";
      
      $bFlag = false;
    }
    else
    {
      $sUitwerking .= "<tr>\n<td>" . strip($_POST['cheat' . $iTeller]) . "</td>\n";
      $sUitwerking .= '<td>' . strip($_POST['uitwerking' . $iTeller]) . "</td>\n</tr>\n";
      
      $bFlag = true;
    }  
  }
  
  $sUitwerking .= '</table>';
  
  $cTPL -> setBlock('FORM', 'uitwerking');
  $cTPL -> parse();
  
  $cTPL -> setPlace('UITWERKING', $sUitwerking);
}
elseif(isset($_GET['aantal']))
{
  $cTPL -> setBlock('FORM', 'form');
  $cTPL -> parse();
  for($iTeller = 0; $iTeller < $_GET['aantal']; $iTeller++)
  {
    $cTPL -> setBlock('REGEL', 'regel');
    $cTPL -> parse();
    
    $cTPL -> setPlace('TELLER', $iTeller);
    $cTPL -> setPlace('AANTAL', $_GET['aantal']);
  }
}

$cTPL -> show();
?>