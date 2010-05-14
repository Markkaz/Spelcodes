<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Permissie controleren */
if(!$cUser -> m_iPermis & 64)
{
  die('Geen permissie...');
}

/* Controleren of het formulier is verzonden */
if(isset($_POST['link']))
{
  $sQuery = "INSERT INTO links (linkid, link, url, incomming, outcomming)
             VALUES ('', '" . add($_POST['link']) . "', '" . add($_POST['url']) . "', 0, 0);";
  if(mysql_query($sQuery))
  {
    header('Location: links.php');
  }
  else
  {
    $cTPL -> setPlace('TITEL', 'Admin - Fout met database');
    $cTPL -> setPlace('CONTENT', 'Door een fout met de database kon je request niet verwerkt worden');
  }
}
else
{
  $cTPL -> setPlace('TITEL', 'Admin - Link toevoegen');
  $cTPL -> setFile('CONTENT', 'Templates/linkToevoeg.tpl');
}

$cTPL -> show();
?>