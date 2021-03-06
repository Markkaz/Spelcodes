<?php
include('Includes/header.php');

/* Controleren of je bent ingelogd */
if((!$cUser -> checkSession()) && (!$cUser -> checkCookie()))
{
  header('Location: ../../loginForm.php');
}

/* Permissie controleren */
if(!$cUser -> m_iPermis & 4)
{
  die('Geen permissie...');
}

$cTPL -> setPlace('TITEL', 'Forum - Admin');
$cTPL -> setFile('CONTENT', 'Templates/index.tpl');
$cTPL -> parse();

/* Forum block bewaren */
$cTPL -> setBlock('EMPTY', 'forum');

/* CategorieŽn ophalen */
$sQuery = "SELECT cat_id, cat_titel FROM forum_categories ORDER BY cat_order;";
if($cResultCats = mysql_query($sQuery))
{
  while($aDataCats = mysql_fetch_assoc($cResultCats))
  {
    $cTPL -> setBlock('CAT', 'categorie');
    $cTPL -> parse();
    
    $cTPL -> setPlace('NAAM', $aDataCats['cat_titel']);
    $cTPL -> setPlace('CATID', $aDataCats['cat_id']);
    $cTPL -> parse();
    
    /* Fora ophalen */
    $sQuery = "SELECT forum_id, forum_titel, forum_text FROM forum_forums
               WHERE cat_id='" . $aDataCats['cat_id'] . "' ORDER BY forum_id;";
    if($cResultFora = mysql_query($sQuery))
    {
      while($aDataFora = mysql_fetch_assoc($cResultFora))
      {
        $cTPL -> setBlock('FORUM', 'forum');
        $cTPL -> parse();
        
        $cTPL -> setPlace('TITEL', $aDataFora['forum_titel']);
        $cTPL -> setPlace('BESCHRIJVING', $aDataFora['forum_text']);
        $cTPL -> setPlace('FORUMID', $aDataFora['forum_id']);
      }
      $cTPL -> setPlace('FORUM', '');
    }
  }
}

/* Formulier voor forum toevoegen maken */
$sQuery = "SELECT cat_id, cat_titel FROM forum_categories ORDER BY cat_order;";
if($cResult = mysql_query($sQuery))
{
  while($aData = mysql_fetch_assoc($cResult))
  {
    $cTPL -> setBlock('OPTION', 'option');
    $cTPL -> parse();
  
    $cTPL -> setPlace('OPTIONCATID', $aData['cat_id']);
    $cTPL -> setPlace('CATTITEL', $aData['cat_titel']);
  }
}

$cTPL -> show();
?>