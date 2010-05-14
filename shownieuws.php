<?php
error_reporting(E_ALL);

session_start();

/* Classes importeren */
include('Classes/User.php');
include('Classes/Template.php');

/* Includes importeren */
include('Includes/connect.php');
include('Includes/slashes.php');
include('Includes/smiley.php');
include('Includes/bbcode.php');

/* Classes initialiseren */
$cUser = new user();
$cTPL = new Template('Templates/main.tpl');

/* Verbinding met de database maken */
connectDB();

/* Controleren of er wel een id is meegegeven */
if(isset($_GET['id']))
{
  include('Includes/login.php');
  
  $cTPL -> setFile('CONTENT', 'Templates/shownieuws.tpl');
  $cTPL -> parse();
  
  $sQuery = "SELECT n.titel, n.bericht, u.username, n.datum, n.tijd FROM nieuws n, users u
             WHERE u.userid = n.userid AND n.nieuwsid='" . add($_GET['id']) . "';";
  if($cResult = mysql_query($sQuery))
  {
    $aData = mysql_fetch_assoc($cResult);
    $cTPL -> setPlace('TITEL', add($aData['titel']));
    $cTPL -> setPlace('USERNAME', add($aData['username']));
    $cTPL -> setPlace('BERICHT', smiley(strip($aData['bericht'])));
    $cTPL -> setPlace('DATUM', add($aData['datum'] . ' ' . $aData['tijd']));
    $cTPL -> parse();
  }
  
  $cTPL -> setPlace('ID', $_GET['id']);
  
  $sQuery = "SELECT n.reactieid, n.bericht, u.userid, u.username, n.datum, n.tijd FROM nieuwsreacties n, users u
             WHERE n.userid=u.userid AND n.nieuwsid='" . add($_GET['id']) . "'
             ORDER BY n.datum, n.tijd;";
  if($cResult = mysql_query($sQuery))
  {
    while($aData = mysql_fetch_assoc($cResult))
    {
      $cTPL -> setBlock('REACTIES', 'reacties');
      $cTPL -> parse();
      
      $cTPL -> setPlace('AUTEUR', add($aData['username']));
      $cTPL -> setPlace('MOMENT', add($aData['datum'] . ' ' . $aData['tijd']));
      $cTPL -> setPlace('REACTIE', bbcode(smiley(strip_tags(strip($aData['bericht'])))));
      
      if(($cUser -> m_iPermis & 2) || ($aData['userid'] == $cUser -> m_iUserid))
      {
        $cTPL -> setBlock('REACTIEEDIT', 'edit');
        $cTPL -> parse();
        $cTPL -> setPlace('REACTIEID', $aData['reactieid']);
      }
      else
      {
        $cTPL -> setPlace('REACTIEEDIT', '');
      }
      $cTPL -> parse();
    }
  }
  
  $cTPL -> show();
}
else
{
  header('HTTP/1.0 404 Page not Found');
}
?>