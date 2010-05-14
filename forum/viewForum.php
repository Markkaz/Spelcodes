<?php
/* Header importeren */
include('Includes/header.php');

/* Navigatie class importeren */
include('Classes/navigatie.php');

/* Controleren of er een forumid is */
if(!isset($_GET['forumid']))
{
  die('Geen forumid...');
}

$cTPL -> setPlace('TITEL', 'Forum - Topics');
$cTPL -> setFile('CONTENT', 'Templates/viewForum.tpl');
$cTPL -> parse();

/* Data ophalen en verwerken */
$sQuery = "SELECT t.topic_id, t.topic_titel, u.userid, u.username, t.topic_time, t.topic_replies, t.topic_status, t.topic_views, t.last_post, t.last_post_time
           FROM forum_topics t, users u 
           WHERE t.forum_id='" . add($_GET['forumid']) . "' AND u.userid=t.topic_poster
           ORDER BY t.last_post_time DESC;";
if($cResult = mysql_query($sQuery))
{
  while($aData = mysql_fetch_assoc($cResult))
  {
    $cTPL -> setBlock('TOPIC', 'topic');
    $cTPL -> parse();
    
    $cTPL -> setPlace('TOPICID', $aData['topic_id']);
    $cTPL -> setPlace('TITEL', $aData['topic_titel']);
    $cTPL -> setPlace('POSTER', $aData['username']);
    $cTPL -> setPlace('MOMENT', $aData['topic_time']);
    $cTPL -> setPlace('LAST_POST', $cUser -> getUsername($aData['last_post']));
    $cTPL -> setPlace('LAST_POST_TIME', $aData['last_post_time']);
    
    if(($aData['userid'] == $cUser -> m_iUserid) || ($cUser -> m_iPermis & 2))
    {
      $cTPL -> setBlock('EDIT', 'edit');
      
      /* Controleren of je forum mod bent */
      if($cUser -> m_iPermis & 2)
      {
        $cTPL -> setBlock('MOVE', 'move');
      }
      $cTPL -> parse();
      
      $cTPL -> setPlace('TOPICIDADMIN', $aData['topic_id']);
    }
    else
    {
      $cTPL -> setPlace('EDIT', '');
    }
    
    $cNav = new Navigatie($aData['topic_id']);
    $cTPL -> setPlace('NUMMERS', $cNav -> makeNavigation());
    
    /* Aantal posts in topic ophalen */
    $sQuery = "SELECT count(post_id) AS posts FROM forum_posts WHERE topic_id='" . add($aData['topic_id']) . "';";
    if($cPostsResult = mysql_query($sQuery))
    {
      $aPostsData = mysql_fetch_assoc($cPostsResult);
      $cTPL -> setPlace('AANTAL_POSTS', $aPostsData['posts']);
    }
    $cTPL -> parse();
  }
}

$cTPL -> setPlace('FORUMID', $_GET['forumid']);
$cTPL -> show();
?>