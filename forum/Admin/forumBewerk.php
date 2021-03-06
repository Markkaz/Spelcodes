<?php
/* Header importeren */
include('Includes/header.php');

/* Controleren of er een forumid is */
if(!isset($_GET['forumid']))
{
  die('Geen forumid...');
}

/* Controleren of je bent ingelogd */
if((!$cUser -> checkSession()) && (!$cUser -> checkCookie()))
{
  header('Location: ../../loginForm.php');
  exit;
}

/* Permissie controleren */
if(!$cUser -> m_iPermis & 4)
{
  die('Geen permissie...');
}

/* Controleren of het formulier is verzonden */
if(isset($_POST['titel']))
{
  $sQuery = "UPDATE forum_forums SET cat_id='" . add($_POST['catid']) . "', forum_titel='" . add($_POST['titel']) . "',
             forum_text='" . add($_POST['beschrijving']) . "'
             WHERE forum_id='" . add($_GET['forumid']) . "';";
  if(mysql_query($sQuery))
  {
    header('Location: index.php');
  }
  else
  {
    $cTPL -> setPlace('TITEL', 'Fout met database');
    $cTPL -> setPlace('CONTENT', 'Door een fout met de database kon het forum niet worden bewerkt.');
  }
}
else
{
  $cTPL -> setPlace('TITEL', 'Forum - Admin - Forum bewerken');
  $cTPL -> setFile('CONTENT', 'Templates/forumBewerk.tpl');
  $cTPL -> parse();
  
  /* Data ophalen */
  $sQuery = "SELECT cat_id, forum_titel, forum_text FROM forum_forums WHERE forum_id='" . add($_GET['forumid']) . "';";
  if($cResult = mysql_query($sQuery))
  {
    $aData = mysql_fetch_assoc($cResult);
    
    $cTPL -> setPlace('TITEL', $aData['forum_titel']);
    $cTPL -> setPlace('BESCHRIJVING', $aData['forum_text']);
    
    /* Select menu maken */
    $sQuery = "SELECT cat_id, cat_titel FROM forum_categories ORDER BY cat_order;";
    if($cResult = mysql_query($sQuery))
    {
      while($aDataOption = mysql_fetch_assoc($cResult))
      {
        $cTPL -> setBlock('OPTION', 'option');
        $cTPL -> parse();
        
        $cTPL -> setPlace('CATID', $aDataOption['cat_id']);
        $cTPL -> setPlace('CATEGORIE', $aDataOption['cat_titel']);
        if($aData['cat_id'] == $aDataOption['cat_id'])
        {
          $cTPL -> setPlace('SELECTED', ' "selected"');
        }
        $cTPL -> parse();
      }
    }
  }
  
  $cTPL -> setPlace('FORUMID', $_GET['forumid']);
}

$cTPL -> show();
?>