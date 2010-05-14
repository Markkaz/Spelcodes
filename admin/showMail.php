<?php
/* Header importeren */
include('Includes/header.php');

/* Controleren of er een mailid is */
if(!isset($_GET['mailid']))
{
  die('Geen mailid...');
}

/* Permissie controleren */
if(!$cUser -> m_iPermis & 1024)
{
  die('Geen permissie...');
}

$cTPL -> setPlace('TITEL', 'Admin - Bekijk mail');
$cTPL -> setFile('CONTENT', 'Templates/showMail.tpl');
$cTPL -> parse();

$sQuery = "SELECT titel, bericht, email FROM mail WHERE mailid='" . add($_GET['mailid']) . "';";
if($cResult = mysql_query($sQuery))
{
  $aData = mysql_fetch_assoc($cResult);
  $cTPL -> setPlace('TITEL', $aData['titel']);
  $cTPL -> setPlace('EMAIL', $aData['email']);
  $cTPL -> setPlace('BERICHT', nl2br(htmlspecialchars($aData['bericht'])));
}

/* Het bericht moet nu gelezen zijn */
$sQuery = "UPDATE mail SET gelezen=1;";
mysql_query($sQuery);

$cTPL -> show();
?>