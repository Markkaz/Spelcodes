<?php
/* Header importeren */
include('Includes/header.php');

$cTPL -> setPlace('TITEL', 'Forum - Index');
$cTPL -> setFile('CONTENT', 'Templates/index.tpl');
$cTPL -> parse();

/* Data ophalen en verwerken */
$sQuery = "SELECT cat_id, cat_titel FROM forum_categories ORDER BY cat_order;";
if($cResultCat = mysql_query($sQuery))
{
  while($aDataCat = mysql_fetch_assoc($cResultCat))
  {
    $cTPL -> setBlock('CAT', 'categorie');
    $cTPL -> parse();
    
    $cTPL -> setPlace('NAAM', $aDataCat['cat_titel']);
    
    $sQuery = "SELECT forum_id, forum_titel, forum_text, last_post, last_post_time FROM forum_forums
               WHERE cat_id='" . $aDataCat['cat_id'] . "' ORDER BY forum_id;";
    if($cResultForum = mysql_query($sQuery))
    {
      $cTPL -> setBlock('EMPTY', 'forum');
      
      while($aDataForum = mysql_fetch_assoc($cResultForum))
      {
        $cTPL -> setBlock('FORUM', 'forum');
        $cTPL -> parse();
        
        $cTPL -> setPlace('FORUMID', $aDataForum['forum_id']);
        $cTPL -> setPlace('TITEL', $aDataForum['forum_titel']);
        $cTPL -> setPlace('BESCHRIJVING', $aDataForum['forum_text']);
        $cTPL -> setPlace('LAST_POST', $cUser -> getUsername($aDataForum['last_post']));
        $cTPL -> setPlace('LAST_POST_TIME', $aDataForum['last_post_time']);
        
        /* Aantal topics ophalen */
        $sQuery = "SELECT count(topic_id) AS topics FROM forum_topics WHERE forum_id='" . $aDataForum['forum_id'] . "';";
        if($cResult = mysql_query($sQuery))
        {
          $aData = mysql_fetch_assoc($cResult);
          $cTPL -> setPlace('POSTS', $aData['topics']);
        }
      }
      $cTPL -> setPlace('FORUM', '');
    }
  }
}

$cTPL -> show();
?>