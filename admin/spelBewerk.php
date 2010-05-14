<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Permissie controleren */
if(!$cUser -> m_iPermis & 1)
{
  die('Geen permissie...');
}

/* Controleren of er wel een id is meegegeven */
if(!isset($_GET['id']))
{
  die('Geen id...');
}

/* Controleren of het formulier verzonden is */
if(isset($_POST['naam']))
{
  $sQuery = "UPDATE spellen SET naam='" . add($_POST['naam']) . "', consoleid='" . add($_POST['console']) . "', developer='" . add($_POST['developer']) . "',
            publisher='" . add($_POST['publisher']) . "', developerurl='" . add($_POST['developersite']) . "',
            publisherurl='" . add($_POST['publishersite']) . "' WHERE spelid='" . add($_GET['id']) . "';";
  if(mysql_query($sQuery))
  {
    header('Location: spellen.php');
  }
  else
  {
    $cTPL -> setPlace('TITEL', 'Fout in database');
    $cTPL -> setPlace('CONTENT', 'Er is iets mis gegaan met de database');
  } 
}
else
{
  $cTPL -> setPlace('TITEL', 'Admin - Spel bewerken');
  $cTPL -> setFile('CONTENT', 'Templates/spelBewerk.tpl');
  $cTPL -> parse();
  
  $sQuery = "SELECT naam, consoleid, developer, publisher, developerurl, publisherurl
             FROM spellen WHERE spelid='" . add($_GET['id']) . "';";
  if($cResult = mysql_query($sQuery))
  {
    $aData = mysql_fetch_assoc($cResult);
    $cTPL -> setPlace('ID', $_GET['id']);
    $cTPL -> setPlace('NAAM', $aData['naam']);
    $cTPL -> setPlace('DEVELOPER', $aData['developer']);
    $cTPL -> setPlace('DEVELOPERSITE', $aData['developerurl']);
    $cTPL -> setPlace('PUBLISHER', $aData['publisher']);
    $cTPL -> setPlace('PUBLISHERSITE', $aData['publisherurl']);
  
    $sQuery = "SELECT consoleid, naam FROM consoles ORDER BY naam;";
    if($cResultMenu = mysql_query($sQuery))
    {
      while($aDataMenu = mysql_fetch_assoc($cResultMenu))
      {
        $cTPL -> setBlock('OPTION', 'option');
        $cTPL -> parse();
        
        if($aData['consoleid'] == $aDataMenu['consoleid'])
        {
          $cTPL -> setPlace('SELECT', ' selected');
        }
        else
        {
          $cTPL -> setPlace('SELECT', '');
        }
        
        $cTPL -> setPlace('CONSOLEID', $aDataMenu['consoleid']);
        $cTPL -> setPlace('CONSOLE', $aDataMenu['naam']);
        $cTPL -> parse();
      }
    }
  }
}

$cTPL -> show();
?>