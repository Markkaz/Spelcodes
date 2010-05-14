<?php
/* Header importeren */
include('Includes/header.php');

/* Controleren of er wel een catid is */
if(!isset($_GET['catid']))
{
  die('Geen catid...');
}

/* Controleren of je wel bent ingelogd */
if((!$cUser -> checkSession()) && (!$cUser -> checkCookie()))
{
  header('Location: ../../loginForm.php');
}

/* Permissie controleren */
if(!$cUser -> m_iPermis & 4)
{
  die('Geen permissie...');
}

/* Controleren of het formulier is verzonden */
if(isset($_POST['titel']))
{
  $sQuery = "UPDATE forum_categories
             SET cat_titel='" . add($_POST['titel']) . "', cat_order='" . add($_POST['order']) . "'
             WHERE cat_id='" . add($_GET['catid']) . "';";
  if(mysql_query($sQuery))
  {
    header('Location: index.php');
  }
  else
  {
    $cTPL -> setPlace('TITEL', 'Fout met database');
    $cTPL -> setPlace('CONTENT', 'Door een fout met de database is de categorie niet bewerkt.');
  }
}
else
{
  $cTPL -> setPlace('TITEL', 'Forum - Admin - Categorie bewerken');
  $cTPL -> setFile('CONTENT', 'Templates/catBewerk.tpl');
  $cTPL -> parse();
  
  /* Data ophalen */
  $sQuery = "SELECT cat_titel, cat_order FROM forum_categories WHERE cat_id='" . add($_GET['catid']) . "';";
  if($cResult = mysql_query($sQuery))
  {
    $aData = mysql_fetch_assoc($cResult);
    $cTPL -> setPlace('TITEL', $aData['cat_titel']);
    $cTPL -> setPlace('ORDER', $aData['cat_order']);
  }
  $cTPL -> setPlace('CATID', $_GET['catid']);
}

$cTPL -> show();
?>