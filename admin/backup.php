<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Permissie controleren */
if(!$cUser -> m_iPermis & 512)
{
  die('Geen permissie...');
}

$sBackup = '';

/* Table 'berichten' */
$sQuery = "SELECT berichtid, topicid, userid, bericht, datum, tijd FROM berichten ORDER BY berichtid;";
if($cResult = mysql_query($sQuery))
{
  while($aData = mysql_fetch_assoc($cResult))
  {
    $sBackup .= "INSERT INTO berichten (berichtid, topicid, userid, bericht, datum, tijd) VALUES ('" . add($aData['berichtid']) . "', '" . add($aData['topicid']) . "', '" . add($aData['userid']) . "', '" . add($aData['bericht']) . "', '" . add($aData['datum']) . "', '" . add($aData['tijd']) . "');\n";
  }
  $sBackup .= "\n";
}
else
{
  $cTPL -> setPlace('CONTENT', 'Backuppen van berichten table mislukt...<br>{~CONTENT~}');
  $cTPL -> parse();
}

/* Table 'consoles' */
$sQuery = "SELECT consoleid, naam FROM consoles ORDER BY consoleid;";
if($cResult = mysql_query($sQuery))
{
  while($aData = mysql_fetch_assoc($cResult))
  {
    $sBackup .= "INSERT INTO consoles (consoleid, naam) VALUES ('" . add($aData['consoleid']) . "', '" . add($aData['naam']) . "');\n";
  }
  $sBackup .= "\n";
}
else
{
  $cTPL -> setPlace('CONTENT', 'Backuppen van consoles table mislukt...<br>{~CONTENT~}');
  $cTPL -> parse();
}

/* Table 'links' */
$sQuery = "SELECT linkid, link, url, incomming, outcomming FROM links ORDER BY linkid;";
if($cResult = mysql_query($sQuery))
{
  while($aData = mysql_fetch_assoc($cResult))
  {
    $sBackup .= "INSERT INTO links (linkid, link, url, incomming, outcomming) VALUES ('" . add($aData['linkid']) . "', '" . add($aData['link']) . "', '" . add($aData['url']) . "', '" . add($aData['incomming']) . "', '" . add($aData['outcomming']) . "');\n";
  }
  $sBackup .= "\n";
}
else
{
  $cTPL -> setPlace('CONTENT', 'Backuppen van links table is mislukt...<br>{~CONTENT~}');
  $cTPL -> parse();
}

/* Table 'nieuws' */
$sQuery = "SELECT nieuwsid, userid, titel, bericht, datum, tijd FROM nieuws ORDER BY nieuwsid;";
if($cResult = mysql_query($sQuery))
{
  while($aData = mysql_fetch_assoc($cResult))
  {
    $sBackup .= "INSERT INTO nieuws (nieuwsid, userid, titel, bericht, datum, tijd) VALUES ('" . add($aData['nieuwsid']) . "', '" . add($aData['userid']) . "', '" . add($aData['titel']) . "', '" . add($aData['bericht']) . "', '" . add($aData['datum']) . "', '" . add($aData['tijd']) . "');\n";
  }
  $sBackup .= "\n";
}
else
{
  $cTPL -> setPlace('CONTENT', 'Backuppen van nieuws table is mislukt...<br>{~CONTENT~}');
  $cTPL -> parse();
}

/* Table 'nieuwsreacties' */
$sQuery = "SELECT reactieid, nieuwsid, userid, bericht, datum, tijd FROM nieuwsreacties ORDER BY reactieid";
if($cResult = mysql_query($sQuery))
{
  while($aData = mysql_fetch_assoc($cResult))
  {
    $sBackup .= "INSERT INTO nieuwsreacties (reactieid, nieuwsid, userid, bericht, datum, tijd) VALUES ('" . add($aData['reactieid']) . "', '" . add($aData['nieuwsid']) . "', '" . $aData['userid'] . "', '" . add($aData['bericht']) . "', '" . add($aData['datum']) . "', '" . add($aData['tijd']) . "');\n";
  }
  $sBackup .= "\n";
}
else
{
  $cTPL -> setPlace('CONTENT', 'Backuppen van nieuwsreacties table is mislukt...<br>{~CONTENT~}');
  $cTPL -> parse();
}

/* Table 'spellen' */
$sQuery = "SELECT spelid, consoleid, naam, map, developer, publisher, developerurl, publisherurl, rating, stemmen FROM spellen ORDER BY spelid;";
if($cResult = mysql_query($sQuery))
{
  while($aData = mysql_fetch_assoc($cResult))
  {
    $sBackup .= "INSERT INTO spellen (spelid, consoleid, naam, map, developer, publisher, developerurl, publisherurl, rating, stemmen) VALUES ('" . add($aData['spelid']) . "', '" . add($aData['consoleid']) . "', '" . add($aData['naam']) . "', '" . add($aData['map']) . "', '" . add($aData['developer']) . "', '" . add($aData['publisher']) . "', '" . add($aData['developerurl']) . "', '" . add($aData['publisherurl']) . "', '" . add($aData['rating']) . "', '" . add($aData['stemmen']) . "');\n";
  }
  $sBackup .= "\n";
}
else
{
  $cTPL -> setPlace('CONTENT', 'Backuppen van spellen table is mislukt...<br>{~CONTENT~}');
  $cTPL -> parse();
}

/* Table 'spellenhulp' */
$sQuery = "SELECT spelid, topicid FROM spellenhulp ORDER BY spelid;";
if($cResult = mysql_query($sQuery))
{
  while($aData = mysql_fetch_assoc($cResult))
  {
    $sBackup .= "INSERT INTO spellenhulp (spelid, topicid) VALUES ('" . add($aData['spelid']) . "', '" . add($aData['topicid']) . "');\n";
  }
  $sBackup .= "\n";
}
else
{
  $cTPL -> setPlace('CONTENT', 'Backuppen van spellenhulp table mislukt...<br>{~CONTENT~}');
  $cTPL -> parse();
}

/* Table 'spellenview' */
$sQuery = "SELECT consoleid, spelid FROM spellenview ORDER BY consoleid;";
if($cResult = mysql_query($sQuery))
{
  while($aData = mysql_fetch_assoc($cResult))
  {
    $sBackup .= "INSERT INTO spellenview (consoleid, spelid) VALUES ('" . add($aData['consoleid']) . "', '" . add($aData['spelid']) . "');\n";
  }
  $sBackup .= "\n";
}
else
{
  $cTPL -> setPlace('CONTENT', 'Backuppen van spellenview table mislukt...<br>{~CONTENT~}');
  $cTPL -> parse();
}

/* Table 'stemmen' */
$sQuery = "SELECT spelid, ip FROM stemmen ORDER BY spelid;";
if($cResult = mysql_query($sQuery))
{
  while($aData = mysql_fetch_assoc($cResult))
  {
    $sBackup .= "INSERT INTO stemmen (spelid, ip) VALUES ('" . add($aData['spelid']) . "', '" . add($aData['ip']) . "');\n";
  }
  $sBackup .= "\n";
}
else
{
  $cTPL -> setPlace('CONTENT', 'Backuppen van stemmen table mislukt...<br>{~CONTENT~}');
  $cTPL -> parse();
}

/* Table 'topics' */
$sQuery = "SELECT topicid, userid, titel, bericht, datum, tijd FROM topics ORDER BY topicid;";
if($cResult = mysql_query($sQuery))
{
  while($aData = mysql_fetch_assoc($cResult))
  {
    $sBackup .= "INSERT INTO topics (topicid, userid, titel, bericht, datum, tijd) VALUES ('" . add($aData['topicid']) . "', '" . add($aData['userid']) . "', '" . add($aData['titel']) . "', '" . add($aData['bericht']) . "', '" . add($aData['datum']) . "', '" . add($aData['tijd']) . "');\n";
  }
  $sBackup .= "\n";
}
else
{
  $cTPL -> setPlace('CONTENT', 'Backuppen van topics table mislukt...<br>{~CONTENT~}');
  $cTPL -> parse();
}

/* Table 'users' */
$sQuery = "SELECT userid, username, password, email, ip, activate, permis, posts, datum FROM users ORDER BY userid;";
if($cResult = mysql_query($sQuery))
{
  while($aData = mysql_fetch_assoc($cResult))
  {
    $sBackup .= "INSERT INTO users (userid, username, password, email, ip, activate, permis, posts, datum) VALUES ('" . add($aData['userid']) . "', '" . add($aData['username']) . "', '" . add($aData['password']) . "', '" . add($aData['email']) . "', '" . add($aData['ip']) . "', '" . add($aData['activate']) . "', '" . add($aData['permis']) . "', '" . add($aData['posts']) . "', '" . add($aData['datum']) . "');\n";
  }
  $sBackup .= "\n";
}
else
{
  $cTPL -> setPlace('CONTENT', 'Backuppen van users table mislukt...<br>{~CONTENT~}');
  $cTPL -> parse();
}

/* Table 'forum_categories' */
$sQuery = "SELECT cat_id, cat_titel, cat_order FROM forum_categories ORDER BY cat_id;";
if($cResult = mysql_query($sQuery))
{
  while($aData = mysql_fetch_assoc($cResult))
  {
    $sBackup .= "INSERT INTO forum_categories (cat_id, cat_titel, cat_order) VALUES ('" . add($aData['cat_id']) . "', '" . add($aData['cat_titel']) . "', '" . add($aData['cat_order']) . "');\n";
  }
  $sBackup .= "\n";
}
else
{
  $cTPL -> setPlace('CONTENT', 'Backuppen van forum_categories table mislukt...<br>{~CONTENT~}');
  $cTPL -> parse();
}

/* Table 'forum_forums' */
$sQuery = "SELECT forum_id, cat_id, forum_titel, forum_text, last_post, last_post_time FROM forum_forums ORDER BY forum_id;";
if($cResult = mysql_query($sQuery))
{
  while($aData = mysql_fetch_assoc($cResult))
  {
    $sBackup .= "INSERT INTO forum_forums (forum_id, cat_id, forum_titel, forum_text, last_post, last_post_time) VALUES ('" . add($aData['forum_id']) . "', '" . add($aData['cat_id']) . "', '" . add($aData['forum_titel']) . "', '" . add($aData['forum_text']) . "', '" . add($aData['last_post']) . "', '" . add($aData['last_post_time']) . "');\n";
  }
  $sBackup .= "\n";
}
else
{
  $cTPL -> setPlace('CONTENT', 'Backuppen van forum_forums table mislukt...<br>{~CONTENT~}');
  $cTPL -> parse();
}

/* Table 'forum_posts' */
$sQuery = "SELECT post_id, topic_id, post_poster, post_time, post_titel, post_text FROM forum_posts ORDER BY post_id;";
if($cResult = mysql_query($sQuery))
{
  while($aData = mysql_fetch_assoc($cResult))
  {
    $sBackup .= "INSERT INTO forum_posts (post_id, topic_id, post_poster, post_time, post_titel, post_text) VALUES ('" . add($aData['post_id']) . "', '" . add($aData['topic_id']) . "', '" . add($aData['post_poster']) . "', '" . add($aData['post_time']) . "', '" . add($aData['post_text']) . "');\n";
  }
  $sBackup .= "\n";
}
else
{
  $cTPL -> setPlace('CONTENT', 'Backuppen van forum_posts table mislukt...<br>{~CONTENT~}');
  $cTPL -> parse();
}

/* Table 'forum_topics' */
$sQuery = "SELECT topic_id, forum_id, topic_titel, topic_poster, topic_time, topic_replies, topic_status, topic_views, last_post, last_post_time FROM forum_topics ORDER BY topic_id;";
if($cResult = mysql_query($sQuery))
{
  while($aData = mysql_fetch_assoc($cResult))
  {
    $sBackup .= "INSERT INTO forum_topics (topic_id, forum_id, topic_titel, topic_poster, topic_time, topic_replies, topic_status, topic_views, last_post, last_post_time) VALUES ('" . add($aData['topic_id']) . "', '" . add($aData['forum_id']) . "', '" . add($aData['topic_titel']) . "', '" . add($aData['topic_poster']) . "', '" . add($aData['topic_time']) . "', '" . add($aData['topic_replies']) . "', '" . add($aData['topic_status']) . "', '" . add($aData['topic_views']) . "', '" . add($aData['last_post']) . "', '" . add($aData['last_post_time']) . "');\n";
  }
  $sBackup .= "\n";
}
else
{
  $cTPL -> setPlace('CONTENT', 'Backuppen van forum_topics table mislukt...<br>{~CONTENT~}');
  $cTPL -> parse();
}

if($cFP = fopen('backup/' . date('Y-m-d') . date('-H-i') . '.txt', 'w'))
{
  fwrite($cFP, $sBackup);
  fclose($cFP);
  $cTPL -> setPlace('CONTENT', 'Backup gelukt...');
}
else
{
  $cTPL -> setPlace('CONTENT', 'Backup mislukt...');
}

$cTPL -> setPlace('TITEL', 'Admin - Backup gemaakt');

$cTPL -> show();
?>