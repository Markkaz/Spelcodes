<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Permissie controleren */
if(!$cUser -> m_iPermis & 32)
{
  die('Geen permissie...');
}

/* Controleren of het formulier is verzonden */
if(isset($_POST['titel']))
{
  $sQuery = "INSERT INTO nieuws (nieuwsid, userid, titel, bericht, datum, tijd)
             VALUES ('', '" . $cUser -> m_iUserid . "', '" . add($_POST['titel']) . "',
                     '" . add($_POST['bericht']) . "', NOW(), NOW());";
  if(mysql_query($sQuery))
  {
    header('Location: nieuws.php');
  }
  else
  {
    $cTPL -> setPlace('TITEL', 'Fout met database');
    $cTPL -> setPlace('CONTENT', 'Doordat er iets fout ging met de database, is je request niet verwerkt.');
  }
}
else
{
  $cTPL -> setPlace('TITEL', 'Admin - Nieuws toevoegen');
  $cTPL -> setFile('CONTENT', 'Templates/nieuwsToevoeg.tpl');
}

$cTPL -> show();
?>