<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Controleren op id */
if(!isset($_GET['id']))
{
  die('Geen id...');
}

/* Permissie controleren */
if(!$cUser -> m_iPermis & 64)
{
  die('Geen permissie...');
}

/* Controleren of het formulier is verzonden */
if(isset($_POST['link']))
{
  $sQuery = "UPDATE links SET link='" . add($_POST['link']) . "', url='" . add($_POST['url']) . "'
             WHERE linkid='" . add($_GET['id']) . "';";
  if(mysql_query($sQuery))
  {
    header('Location: links.php');
  }
  else
  {
    $cTPL -> setPlace('TITEL', 'Fout met database');
    $cTPL -> setPlace('CONTENT', 'Door een fout met de database is je request niet verwerkt.');
  }
}
else
{
  $cTPL -> setPlace('TITEL', 'Admin - Link bewerken');
  $cTPL -> setFile('CONTENT', 'Templates/linkBewerk.tpl');
  $cTPL -> parse();
  
  /* Data ophalen en verwerken */
  $sQuery = "SELECT link, url FROM links WHERE linkid='" . add($_GET['id']) . "';";
  if($cResult = mysql_query($sQuery))
  {
    $aData = mysql_fetch_assoc($cResult);
    $cTPL -> setPlace('LINK', $aData['link']);
    $cTPL -> setPlace('URL', $aData['url']);
    $cTPL -> setPlace('ID', $_GET['id']);
  }
}

$cTPL -> show();
?>